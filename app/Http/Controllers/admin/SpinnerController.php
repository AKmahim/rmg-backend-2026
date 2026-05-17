<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\spinner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SpinnerController extends Controller
{
    // ─── Send OTP ────────────────────────────────────────────────────────────────

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

