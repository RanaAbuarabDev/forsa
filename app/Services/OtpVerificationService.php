<?php

namespace App\Services;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OtpVerificationService
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Verify the OTP for the provided email address and activate the user account.
     * If the OTP is valid, the user's email will be marked as verified.
     * A JWT token will also be generated for the user.
     *
     * @param string $email The email address of the user to verify the OTP for.
     * @param string $otp The OTP to verify.
     * @return array An associative array containing the success status, user data, and JWT token if successful, or an error message and code if failed.
     */
    public function verify(string $email, string $otp): array
    {
       
        $user = User::where('email', $email)->first();

        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'المستخدم غير موجود',
                'code' => 404
            ];
        }

       
        $otpVerified = $this->otpService->verifyOtp($email, $otp, 'registration');

    
        if (!$otpVerified['success']) {
            return [
                'success' => false,
                'message' => 'رمز منتهي الصلاحية',
                'code' => 400
            ];
        }

       
        $user->email_verified_at = now();
        $user->save();

       
        $token = JWTAuth::fromUser($user);

        
        return [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];
    }
}
