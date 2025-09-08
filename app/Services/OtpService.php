<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Mail\RegistrationOtpMail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Send an OTP (One Time Password) to the given email address for a specified type.
     * The OTP will be stored in the database and sent via email.
     *
     * @param string $email The email address to send the OTP to.
     * @param string $type The type of OTP being sent ('password_reset' or 'registration').
     * @return void
     */
    public function sendOtp($email, $type = 'password_reset')
    {
        
        $otp = mt_rand(1000, 9999);
    
       
        DB::table('password_resets')
            ->where('email', $email)
            ->where('type', $type)
            ->delete();
 
        DB::table('password_resets')->insert([
            'email' => $email,
            'otp' => $otp,
            'type' => $type,
            'created_at' => now(),
        ]);
    
   
        if ($type === 'registration') {
            Mail::to($email)->send(new RegistrationOtpMail($otp));
        } else {
            Mail::to($email)->send(new ResetPasswordMail($otp));
        }
    }

    /**
     * Verify the provided OTP for the given email and type.
     * Check if the OTP exists and is valid (not expired).
     *
     * @param string $email The email address to verify the OTP for.
     * @param string $otp The OTP to verify.
     * @param string $type The type of OTP to verify ('password_reset' or 'registration').
     * @return array The result of the OTP verification, with a success status and a message.
     */
    public function verifyOtp($email, $otp, $type = 'password_reset')
    {
        
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('type', $type)
            ->first();

      
        if (!$record) {
            return ['success' => false, 'message' => 'رمز خاطئ'];
        }

        
        if (Carbon::parse($record->created_at)->diffInMinutes(now()) > 5) {
            return ['success' => false, 'message' => 'انتهت صلاحية الرمز ، يرجى طلب الرمز من جديد'];
        }

        DB::table('password_resets')
            ->where('email', $email)
            ->where('type', $type)
            ->delete();

        return ['success' => true];
    }
}
