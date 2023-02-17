<?php

namespace App\Http\Controllers;

use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;

class SmsController extends Controller
{

    public function sendSms(Request $request)
{
        // $accountSid  = env('TWILIO_SID');
        // $authToken   = env('TWILIO_TOKEN');
        // $accountPhone= env('TWILIO_NUMBER');

        $accountSid='ACa0661a540f7275cfb4912b63cde8c89c';
        $authToken='d245fa7bff6befc4115b99095a1a689a';
        $accountPhone='+12545003440';
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
        $request->session()->put('name', $request->name);
        $request->session()->put('mobile_number', $request->mobile_number);
        $request->session()->put('password', $request->password);

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
    $code_sent=$request->verification_code;
   $error="The verification code is incorrect. Please try again.";
if ($mobile_number && $verification_code) {
            if($verification_code==$code_sent){

            $user->name=$request->session()->get('name');
            $user->mobile_number=$mobile_number;
            $user->password=$request->session()->get('password');
            $user->save();
            $request->session()->forget('verification_code');
            
            return redirect()->route('home',$user);
            }
            else {

            return redirect()->route('check-form')->with('message',$error);
                    }
}else {
      return redirect()->route('send-sms');
    }
}

}
