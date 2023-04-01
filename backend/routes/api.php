<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'AuthController@login');
Route::post('login/google', 'AuthController@loginWithGoogle');
Route::post('login/facebook', 'AuthController@loginWithFacebook');
Route::post('register', 'AuthController@register');
Route::get('verify-email/{verification_code}', 'AuthController@verifyEmail');
Route::get('resend-verification-link/{id}', 'AuthController@resendVerificationLink');

Route::group(['middleware' => 'auth'], function () {

    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');

});
