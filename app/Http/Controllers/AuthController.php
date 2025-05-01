<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\OtpAttemptService;
use Illuminate\Http\Request;
use App\Services\OtpService;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Services\OtpRateLimiterService;
use App\Services\OtpVerificationService;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */

    protected $otpService;
    public function __construct(AuthService $authService,OtpService $otpService)
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
    }

    /**
     * Login a user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $response = $this->authService->login($credentials);

        if ($response['status'] === 'error') {
            return ApiResponseService::error($response['message'], $response['code']);
        }

        return ApiResponseService::success([
            'user' => $response['user'],
            'authorisation' => [
                'token' => $response['token'],
                'type' => 'bearer',
            ]
        ], 'Login successful', $response['code']);
    }

     /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $response = $this->authService->register($data);

       // Send OTP to the user's email
       $this->otpService->sendOtp($response['user']->email, 'registration');


        return ApiResponseService::success(
            [
                'user' => $response['user'],
            ], 
            'تم إنشاء الحساب بنجاح. تم إرسال رمز التحقق إلى بريدك الإلكتروني، يرجى التحقق قبل تسجيل الدخول.',
         201);
    }

    



    /**
     * Verify the OTP (One-Time Password) for user registration.
     *
     * @param VerifyOtpRequest $request The request object containing validated email and OTP.
     * @param OtpVerificationService $otpVerificationService Service to verify the OTP.
     * @param OtpAttemptService $attemptService Service to manage verification attempts and temporary lockout.
     * 
     * @return JsonResponse
     * Returns a JSON response containing the authenticated user and token on success,
     * or an error message if the verification fails or the maximum number of attempts is exceeded.
     */

    public function verifyRegisterOtp(
        VerifyOtpRequest $request,
        OtpVerificationService $otpVerificationService,
        OtpAttemptService $attemptService
    ) {
        $email = $request->email;
    
        // التحقق من وجود المستخدم
        $user = \App\Models\User::where('email', $email)->first();
    
        if (!$user) {
            return ApiResponseService::error('المستخدم غير موجود', 404);
        }
    
        // التحقق من الحظر المؤقت
        if ($attemptService->isLocked($email)) {
            $remainingSeconds = $attemptService->getRemainingLockTime($email);
            $minutes = ceil($remainingSeconds / 60);
    
            return ApiResponseService::error(
                "تم تجاوز عدد المحاولات المسموح بها. يرجى المحاولة بعد {$minutes} دقيقة.",
                429,
                [
                    'remaining_seconds' => $remainingSeconds,
                    'locked_until' => now()->addSeconds($remainingSeconds)->toDateTimeString(),
                ]
            );
        }
    
        // التحقق من الرمز
        $result = $otpVerificationService->verify($email, $request->otp, 'registration');
    
        if (!$result['success']) {
            $attemptService->incrementAttempts($email);
    
            if ($attemptService->hasExceededAttempts($email)) {
                $attemptService->applyLock($email);
                return ApiResponseService::error("تم تجاوز عدد المحاولات المسموح بها. يرجى المحاولة بعد 30 دقيقة.", 429);
            }
    
            $remaining = $attemptService->remainingAttempts($email);
            return ApiResponseService::error("رمز التحقق غير صحيح. تبقى {$remaining} محاولات.", 400);
        }
    
        // نجاح
        $attemptService->clearAttempts($email);
    
        $token = JWTAuth::fromUser($user);
    
        return ApiResponseService::success([
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 'تم التحقق من الرمز وتسجيل الدخول بنجاح');
    }

    
    
    


    /**
     * Logout the current user.
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $response = $this->authService->logout();

        return ApiResponseService::success(null, $response['message'], $response['code']);
    }



    /**
    * Send OTP to the given email for password reset.
    *
    * @param \App\Http\Requests\ForgotPasswordRequest $request
    * @return \Illuminate\Http\JsonResponse
    *
    * @throws \Illuminate\Validation\ValidationException
    */
    public function forgotPassword(ForgotPasswordRequest $request, OtpRateLimiterService $limiter){
    $email = $request->email;

    if ($limiter->isLocked($email)) {
        $minutes = ceil($limiter->getRemainingLockTime($email) / 60);
        return ApiResponseService::error("تم حظرك مؤقتًا بسبب عدد محاولات فاشلة كثيرة. يرجى المحاولة بعد {$minutes} دقيقة.", 429);
    }

    $this->otpService->sendOtp($email, 'password_reset');

    return ApiResponseService::success(null, 'تم إرسال رمز التحقق إلى بريدك الإلكتروني');
    }






    /**
    * Verify the provided OTP for the given email.
    *
    * @param \App\Http\Requests\VerifyOtpRequest $request
    * @return \Illuminate\Http\JsonResponse
    *
    * @throws \Illuminate\Validation\ValidationException
    */
    public function verifyOtp(VerifyOtpRequest $request, OtpAttemptService $attemptService){
       
        $email = $request->email;
        
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            return ApiResponseService::error('المستخدم غير موجود', 404);
        }
    

        if ($attemptService->isLocked($email)) {
            $remainingSeconds = $attemptService->getRemainingLockTime($email);
            $minutes = ceil($remainingSeconds / 60);
        
            return ApiResponseService::error(
                "تم تجاوز عدد المحاولات المسموح بها. يرجى المحاولة بعد {$minutes} دقيقة.",
                429,
                [
                    'remaining_seconds' => $remainingSeconds,
                    'locked_until' => now()->addSeconds($remainingSeconds)->toDateTimeString(),
                ]
            );
        }

         $result = $this->otpService->verifyOtp($email, $request->otp, 'password_reset');

         if (!$result['success']) {
            $attempts = $attemptService->incrementAttempts($email);

            if ($attemptService->hasExceededAttempts($email)) {
                 $attemptService->applyLock($email);
             return ApiResponseService::error("تم تجاوز عدد المحاولات المسموح بها. يرجى المحاولة بعد 30 دقيقة.", 429);
            }

        $remaining = $attemptService->remainingAttempts($email);
        return ApiResponseService::error("رمز التحقق غير صحيح. تبقى {$remaining} محاولات.", 400);
    }

        $attemptService->clearAttempts($email); // Reset after success

   

        $token = JWTAuth::fromUser($user);

        return ApiResponseService::success([
            'user' => $user,
            'authorisation' => [
            'token' => $token,
            'type' => 'bearer',
              ]
        ], 'تم التحقق من الرمز وتسجيل الدخول بنجاح');
}

}
    


