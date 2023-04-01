<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepository;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->users->Register($request);

        $origin_url = $request->header('origin');
        if(empty($origin_url)) {
            $origin_url =  'http://localhost:3000';
        }

        $this->users->SendVerificationMail($user, $origin_url);
        return response()->json([
            'message' => Config::get('global.message.register.success'),
            'notify' => Config::get('global.message.register.success'),
        ]);
    }

    public function loginWithGoogle(Request $request)
    {
        try {
            $data = Socialite::driver('google')->userFromToken($request->input('token'));

            $user = $this->users->getUserByEmail($data->email);

            if(empty($user)) {
                $user = $this->users->CreateGoogleUser($data->user);
            }else{
                $user->registered_with = 'google';
                $user->register_id = $data->user['id'];
                if(!$user->email_verified_at) {
                    $user->verification_code = null;
                    $user->email_verified_at = now();
                }
                $user->save();
            }

            return $this->LoginWithSocial($user);

        }catch (RequestException $e) {
            return response()->json(['errors' => [
                'notify' => Config::get('global.message.invalid_google_token')
            ]]);
        }

    }

    public function loginWithFacebook(Request $request)
    {
        try {
            $data = Socialite::driver('facebook')->userFromToken($request->input('token'));

            $user = $this->users->getUserByEmail($data->email);

            if(empty($user)) {
                $user = $this->users->CreateFacebookUser($data);
            }else{
                $user->registered_with = 'facebook';
                $user->register_id = $data->id;
                if(!$user->email_verified_at) {
                    $user->verification_code = null;
                    $user->email_verified_at = now();
                }
                $user->save();
            }


            return $this->LoginWithSocial($user);

        }catch (RequestException $e) {
            return response()->json(['errors' => [
                'notify' => Config::get('global.message.invalid_google_token')
            ]]);
        }

    }

    private function LoginWithSocial($user)
    {
        if (! $token = JWTAuth::fromUser($user)) {
            return response()->json([
                'errors' => [ 'alert' => Config::get('global.message.login.invalid') ]
            ], 422);
        }
        auth()->setUser($user);

        return $this->respondWithToken($token);
    }

    public function verifyEmail($verification_code) {
        $user = $this->users->GetUserFromVerificationCode($verification_code);

        if(empty($user)) {
            return response()->json(['errors' => [
                'alert' => Config::get('global.message.invalid_verify_code')
            ]]);
        }

        $verification_array = $this->users->CheckVerificationCode($verification_code);
        if(!$verification_array) {
            return response()->json(['errors' => [
                'alert' => Config::get('global.message.invalid_verify_code')
            ]]);
        }else{
            $this->users->VerifyAndResetUser($user);
            return response()->json(['message' => Config::get('global.message.user_account_verified')]);
        }
    }

    public function resendVerificationLink($id)
    {
        $user = $this->users->getUserByID($id);

        $origin_url = request()->header('origin');
        if(empty($origin_url)) {
            $origin_url =  'http://localhost:3000';
        }

        if($user && $user->email_verified_at) {
            return response()->json([
                'message' => Config::get('global.message.already_verified'),
                'alert' => Config::get('global.message.already_verified'),
            ]);
        }
        $this->users->SendVerificationMail($user, $origin_url);

        return response()->json([
            'message' => Config::get('global.message.verification_mail_sent'),
            'notify' => Config::get('global.message.verification_mail_sent'),
        ]);
    }

    public function login(LoginRequest $request)
    {

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([ 'alert' => Config::get('global.message.login.invalid') ], 422);
        }

        if(auth()->user()->email_verified_at) {
            return $this->respondWithToken($token);
        }

        $user_id = auth()->user()->id;
        auth()->logout();
        return response()->json([
            'is_verified' => false,
            'user_id' => $user_id,
            'alert' => Config::get('global.message.unverified_account')
        ], 422);

    }

    public function me()
    {
        $user = $this->users->getUserDetailByID(auth()->user()->id);
        return response()->json([
            'user' => $user,
            'menu' => []
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json( ['message' => 'Successfully logged out'] );
    }


    protected function respondWithToken($token)
    {
        $this->users->UpdateLastLogin(auth()->user()->id);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
