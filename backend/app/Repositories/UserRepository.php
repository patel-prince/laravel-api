<?php

namespace App\Repositories;

use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserRepository
{
    private $users;

    public function __construct(User $user)
    {
        $this->users = $user;
    }

    public function getUserByID($id) {
        try {
            $user = $this->users->findOrFail($id);
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getUserByEmail($email) {
        try {
            $user = $this->users->where('email', $email)->first();
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getUserDetailByID($id) {
        try {
            $user = $this->users->findOrFail($id);
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function UpdateLastLogin($id)
    {
        try{
            $user = $this->users->findOrFail($id);
            $user->last_login = now();
            $user->save();
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function CreateGoogleUser($request)
    {
        try{
            $data = [];
            $data['firstname'] = $request['given_name'];
            $data['lastname'] = $request['family_name'];
            $data['image_url'] = $request['picture'];
            $data['email'] = $request['email'];
            $data['verification_code'] = null;
            $data['email_verified_at'] = now();
            $data['registered_with'] = 'google';
            $data['register_id'] = $request['id'];
            $user = $this->users->create($data);
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function CreateFacebookUser($request)
    {
        try{
            $data = [];
            $name = explode(' ', $request->name);
            $data['firstname'] = isset($name[0]) ? $name[0] : '';
            $data['lastname'] = isset($name[1]) ? $name[1] : '';
            $data['image_url'] = $request->avatar;
            $data['email'] = $request->email;
            $data['verification_code'] = null;
            $data['email_verified_at'] = now();
            $data['registered_with'] = 'facebook';
            $data['register_id'] = $request->id;
            $user = $this->users->create($data);
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function Register($request)
    {
        try {
            $data = [];
            $data['firstname'] = $request->firstname;
            $data['lastname'] = $request->lastname;
            $data['email'] = $request->email;
            $data['password'] = app('hash')->make($request->password);
            $user = $this->users->create($data);
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function SendVerificationMail($user, $origin_url)
    {
        try{
            $random_number = mt_rand(1000000000,9999999999);
            $date = strtotime(date('Y-m-d H:i:s'));
            $verification_code = base64_encode($user->id .'###'. base64_encode($random_number) . '###' . base64_encode($date));
            $origin_url = $origin_url . '/verify-email/' . $verification_code;

            $user->verification_code = $verification_code;
            $user->save();

            $data = ['redirect_url' => $origin_url];
            Mail::to($user->email)->send(new VerificationMail($data));
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function CheckVerificationCode($verification_code)
    {
        $verification_array = explode('###', base64_decode($verification_code));
        $token_time = base64_decode($verification_array[2]);
        $token_hours = abs($token_time - strtotime(date('Y-m-d H:i:s')))/(60*60);

        if($token_hours >= env('VERIFICATION_CODE_EXPIRY', 2)) {
            return false;
        }
        return $verification_array;
    }

    public function GetUserFromVerificationCode($verification_code)
    {
        try {
            $user = $this->users->where('verification_code', '=', $verification_code)->first();
            return $user;
        } catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function VerifyAndResetUser($user)
    {
        try{
            $user->verification_code = null;
            $user->email_verified_at = now();
            $user->save();
        }catch (InvalidQueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }
}
