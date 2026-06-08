<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\spinner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

// Prize labels matching the frontend campaign-data.ts prizes array
// index 0 = 1st prize (score 75-90), 1 = 2nd (60-74), 2 = 3rd (50-59), -1 = no prize

class SpinnerController extends Controller
{
    // Prize label map (mirrors frontend prizes array)
    private const PRIZE_LABELS = [
        0  => '১ম পুরস্কার',
        1  => '২য় পুরস্কার',
        2  => '৩য় পুরস্কার',
        -1 => 'কোনো পুরস্কার নেই',
    ];

    // ─── Admin: List with filters ─────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $query = spinner::query();

        if ($request->filled('phone')) {
            $query->where('phone_number', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('prize')) {
            $score = (int) $request->prize;
            if ($score === 0) {         // 1st prize
                $query->whereBetween('score', [75, 90]);
            } elseif ($score === 1) {   // 2nd prize
                $query->whereBetween('score', [60, 74]);
            } elseif ($score === 2) {   // 3rd prize
                $query->whereBetween('score', [50, 59]);
            } elseif ($score === -1) {  // no prize
                $query->where(function ($q) {
                    $q->whereNull('score')->orWhere('score', '<', 50);
                });
            }
        }

        $totalPlayers  = (clone $query)->count();
        $prizeWinners  = (clone $query)->where('score', '>=', 50)->count();
        $totalPlayCount = (clone $query)->sum('played_count');

        $spinners = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.spinner.index', compact('spinners', 'totalPlayers', 'prizeWinners', 'totalPlayCount'));
    }

    // ─── Admin: Export CSV ────────────────────────────────────────────────────

    public function exportCsv(Request $request)
    {
        $query = spinner::query();

        if ($request->filled('phone')) {
            $query->where('phone_number', 'like', '%' . $request->phone . '%');
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('prize')) {
            $score = (int) $request->prize;
            if ($score === 0) {
                $query->whereBetween('score', [75, 90]);
            } elseif ($score === 1) {
                $query->whereBetween('score', [60, 74]);
            } elseif ($score === 2) {
                $query->whereBetween('score', [50, 59]);
            } elseif ($score === -1) {
                $query->where(function ($q) {
                    $q->whereNull('score')->orWhere('score', '<', 50);
                });
            }
        }

        $records = $query->orderByDesc('created_at')->get();

        $filename = 'spinner_data_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['#', 'Phone Number', 'Score', 'Prize', 'Played Count', 'IP Address', 'User Agent', 'Date']);

            foreach ($records as $i => $row) {
                $prize = $this->getPrizeLabel($row->score);
                fputcsv($handle, [
                    $i + 1,
                    $row->phone_number,
                    $row->score ?? 0,
                    $prize,
                    $row->played_count,
                    $row->ip_address,
                    $row->user_agent,
                    $row->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Helper: derive prize label from score
    private function getPrizeLabel(?int $score): string
    {
        if ($score === null)      return self::PRIZE_LABELS[-1];
        if ($score >= 75)        return self::PRIZE_LABELS[0];
        if ($score >= 60)        return self::PRIZE_LABELS[1];
        if ($score >= 50)        return self::PRIZE_LABELS[2];
        return self::PRIZE_LABELS[-1];
    }



    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'অবৈধ মোবাইল নম্বর।',
            ], 422);
        }

        $phone = $request->phone;
        $otp   = strval(rand(1000, 9999));

        // Store OTP with 5-minute TTL (key scoped to phone)
        Cache::put("spinner_otp_{$phone}", $otp, now()->addMinutes(5));

        $message = "আপনার GP স্পিনার OTP কোড: {$otp}। কাউকে জানাবেন না।";

        Http::get('http://apibd.rmlconnect.net/bulksms/personalizedbulksms', [
            'username'    => 'XRInteractive',
            'password'    => 'Q19CvmjM',
            'source'      => '8809617613619',
            'destination' => '88' . $phone,
            'message'     => $message,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'OTP পাঠানো হয়েছে।',
        ]);
    }

    // ─── Verify OTP ──────────────────────────────────────────────────────────────

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'otp'   => ['required', 'digits:4'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $phone       = $request->phone;
        $cachedOtp   = Cache::get("spinner_otp_{$phone}");

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return response()->json([
                'status'  => 'error',
                'message' => 'OTP ভুল বা মেয়াদ শেষ।',
            ], 422);
        }

        // OTP is valid — remove it so it cannot be reused
        Cache::forget("spinner_otp_{$phone}");

        return response()->json([
            'status'  => 'success',
            'message' => 'OTP যাচাই সফল।',
        ]);
    }

    // ─── Save Score ───────────────────────────────────────────────────────────────
    // Rules:
    //   • First play  → create record, store score & prize_index
    //   • Repeat play → increment played_count only; prize is NOT updated

    public function saveScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'       => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'score'       => ['required', 'integer', 'min:0'],
            'prize_index' => ['required', 'integer', 'min:-1'],
            'qr'          => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $phone      = $request->phone;
        $existing   = spinner::where('phone_number', $phone)->first();

        if ($existing) {
            // Returning player — only increment played_count
            $existing->increment('played_count');

            return response()->json([
                'status'        => 'success',
                'first_time'    => false,
                'played_count'  => $existing->played_count,
                'message'       => 'আপনি ইতিমধ্যে পুরস্কার পেয়েছেন। খেলার সংখ্যা আপডেট হয়েছে।',
            ]);
        }

        // First-time player — create record with score & prize
        $record = spinner::create([
            'phone_number' => $phone,
            'score'        => $request->score,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'played_count' => 1,
            'qr'           => $request->input('qr'),
        ]);

        return response()->json([
            'status'       => 'success',
            'first_time'   => true,
            'played_count' => 1,
            'prize_index'  => $request->prize_index,
            'message'      => 'স্কোর সংরক্ষিত হয়েছে।',
        ], 201);
    }

    // ─── Check if phone already played (optional helper) ─────────────────────────

    public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'অবৈধ মোবাইল নম্বর।',
            ], 422);
        }

        $record = spinner::where('phone_number', $request->phone)->first();

        return response()->json([
            'status'       => 'success',
            'has_played'   => (bool) $record,
            'played_count' => $record ? $record->played_count : 0,
        ]);
    }
}

