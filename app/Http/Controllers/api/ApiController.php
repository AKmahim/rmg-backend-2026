<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteView;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\User;    
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    //

    // random number generator
    protected function randomNumber()
    {
        // Generate a random number 6 digits
        $randomNumber = rand(100000, 999999);

        return strval($randomNumber);
    }


    // ========================== otp send controller route mobile gateway =============
    /**
     * Send SMS using Route Mobile API
     *
     * @param  string  $phone
     * @param  string  $message
     * @return \Illuminate\Http\Client\Response
     */
    protected function sendSmsViaRouteMobile($phone, $message)
    {
        $response = Http::get('http://apibd.rmlconnect.net/bulksms/personalizedbulksms', [
            'username' => 'XRInteractive',
            'password' => 'Q19CvmjM',
            'source' => '8809617613619',
            'destination' => '88'.$phone,
            'message' => $message,
        ]);

        return $response;
    }


    


     // site view
    public function siteView(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_url' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $country = null;

       

        SiteView::create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'page_url' => $request->page_url,
            'country' => $country,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Site view recorded successfully',
        ]);
    }




        // logout route
    public function logout(Request $request){
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'User Logout successfully']);
    }

    



}
