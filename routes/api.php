<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SavePostController;
use App\Http\Middleware\EnsureUserHasNoProfile;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FilterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function(){


    

    Route::controller(AuthController::class)->group(function () {
         /**
         * Login Route
         *
         * @method POST
         * @route /v1/login
         * @desc Authenticates a user and returns a JWT token.
         */
        Route::post('login', 'login')->withoutMiddleware('auth:api');

        /**
         * 
         * 
         * Register Route
         *
         * @method POST
         * @route /v1/register
         * @desc Registers a new user and returns a JWT token.
         */
        Route::post('register', 'register')->withoutMiddleware('auth:api');

         /**
         * Logout Route
         *
         * @method POST
         * @route /v1/logout
         * @desc Logs out the authenticated user.
         * @middleware auth:api
         */
        Route::post('logout', 'logout')->middleware('auth:api');

        
        /**
        * Token Refresh Route
        *
         * @method POST
        * @route /v1/refresh
        * @desc Refreshes the user's authentication token.
        * @middleware auth:api
        */
        Route::post('refresh', 'refresh')->middleware('auth:api');


        /**
        * Forgot Password Route
        *
        * @method POST
        * @route /v1/forgot-password
        * @desc Sends an OTP or password reset link to the user's email for password recovery.
        * @middleware none
        */
        Route::post('/forgot-password', 'forgotPassword');
        

        /**
        * Verify OTP Route
        *
        * @method POST
        * @route /v1/verify-otp
        * @desc Verifies the OTP sent to the user's email for actions like password reset or registration.
        * @middleware none
        */
        Route::post('/verify-otp', 'verifyOtp');


        /**
        * Verify Registration OTP Route
        *
        * @method POST
        * @route /v1/verifyRegister-otp
        * @desc Verifies the OTP sent to the user's email during the registration process.
        * @middleware none
        */
        Route::post('/verifyRegister-otp', 'verifyRegisterOtp');
        
    
    });



    /**
    * Update Notifications Setting
    *
    * @method POST
    * @route /user/notifications
    * @desc Updates the user's notifications setting (on/off).
    * @requires JWT Auth (auth:api)
    * @body {
    *   "enabled": boolean
    * }
    * @response {
    *   "message": "تم تحديث الإشعارات"
    * }
    */
    Route::post('/user/notifications', [SettingsController::class, 'updateNotifications']);

    /**
    * Update Theme Setting
    *
    * @method POST
    * @route /user/theme
    * @desc Updates the user's theme preference (dark mode on/off).
    * @requires JWT Auth (auth:api)
    * @body {
    *   "dark_mode": boolean
    * }
    * @response {
    *   "message": "تم تحديث الوضع"
    * }
    */
    Route::post('/user/theme', [SettingsController::class, 'updateTheme']);

    /** 
    * Update User Password
    *
    * @method POST
    * @route /user/update-password
    * @desc Allows the authenticated user to change their password.
    * @requires JWT Auth (auth:api)
    * @body {
    *   "current_password": string,
    *   "new_password": string (min: 6)
    * }
    * @response {
    *   "message": "تم تغيير كلمة المرور"
    * }
    */
    Route::post('/user/update-password', [SettingsController::class, 'updatePassword']);



    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile', [ProfileController::class, 'store'])->middleware(EnsureUserHasNoProfile::class);
        Route::post('/updateProfile', [ProfileController::class, 'update']);
        Route::get('/profile/{userId}', [ProfileController::class, 'showProfile']);
    });
    

    Route::middleware('auth:api')->group(function(){
        Route::post('/create-post', [PostController::class, 'store']);
        Route::get('/posts', [PostController::class, 'index']);
        Route::get('/show-post/{id}', [PostController::class, 'show']);
        Route::post('/update-post/{id}', [PostController::class, 'update']);
        Route::delete('/delete-post/{id}', [PostController::class, 'destroy']);
        Route::get('/posts/my-posts', [PostController::class, 'getMyPosts']);
       
    });


    Route::middleware(('auth:api'))->group(function(){

        Route::get('/posts/filter', [FilterController::class, 'PostFilter']);
        Route::get('/users/filter', [FilterController::class, 'UserFilter']);

        
    });

    Route::middleware('auth:api')->group(function(){
        Route::post('/posts/{postId}/save', [SavePostController::class, 'savePost']);
        Route::get('/user/favorites', [SavePostController::class, 'showFavorites']);
        Route::delete('/user/favorites/{postId}', [SavePostController::class, 'removeFavorite']);
    });

    
    Route::middleware('auth:api')->group(function(){
        Route::post('/application/{post}', [ApplicationController::class, 'store']);
        Route::get('/myApplications',[ApplicationController::class, 'index']);
        Route::delete('/apply/{post}', [ApplicationController::class, 'destroy']);
    });
    

    Route::middleware('auth:api')->group(function(){
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/notifications', [NotificationController::class, 'deleteAllNotifications']);
    });
    
    

});



