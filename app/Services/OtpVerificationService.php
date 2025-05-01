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
        // Retrieve the user based on the provided email address
        $user = User::where('email', $email)->first();

        // If no user found with the provided email, return an error
        if (!$user) {
            return [
                'success' => false,
                'message' => 'المستخدم غير موجود',
                'code' => 404
            ];
        }

        // Verify the OTP using the OtpService
        $otpVerified = $this->otpService->verifyOtp($email, $otp, 'registration');

        // If OTP is invalid or expired, return an error
        if (!$otpVerified['success']) {
            return [
                'success' => false,
                'message' => 'رمز منتهي الصلاحية',
                'code' => 400
            ];
        }

        // Mark the user's email as verified and save it
        $user->email_verified_at = now();
        $user->save();

        // Generate a JWT token for the user
        $token = JWTAuth::fromUser($user);

        // Return a success response with the user data and JWT token
        return [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];
    }
}
