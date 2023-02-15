<?php

namespace App\Http\Controllers;

use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        
        $accountPhone=env('TWILIO_NUMBER');
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_TOKEN');
        $verification_code = mt_rand(100000, 999999);
   
        $client = new Client($accountSid, $authToken);
        $client->messages->create(
        $request->input('mobile_number'),
        [
            'from' => $accountPhone,
            'body' => 'Your verification code is: ' . $verification_code,
        ]
    );    

        $request->session()->put('verification_code', $verification_code);
        $request->session()->put('mobile_number', $request->mobile_number);
         return redirect()->route('verify-sms');    

         }
         public function smsForm(Request $request){
            $mobile_number = $request->session()->get('mobile_number');
            return view('verify-sms', ['mobile_number' => $mobile_number]);
         }

    public function verifySms(Request $request)
    {
    $mobile_number = $request->session()->get('mobile_number');
    $verification_code = $request->session()->get('verification_code');

    if ($mobile_number && $verification_code) {
        return redirect()->route('check-form');
    } else {
        return redirect()->route('send-sms');
    }
}
 
 public function checkSms(Request $request,User $user )
{
    $mobile_number = $request->session()->get('mobile_number');
    $verification_code = $request->session()->get('verification_code');

    if ($mobile_number && $verification_code) {
        $validatedData = $request->validate([
            'verification_code' => 'required|numeric|min:6|max:6',
        ]);

        if ($validatedData['verification_code'] == $verification_code) {

            $request->session()->forget('mobile_number');
            $request->session()->forget('verification_code');
            
            return redirect()->route('data-save');
        } else {
            
            return back()->withErrors(['verification_code' => 'The verification code is incorrect. Please try again.']);
        }
    } else {
      
        return redirect()->route('send-sms');
    }
}

}
