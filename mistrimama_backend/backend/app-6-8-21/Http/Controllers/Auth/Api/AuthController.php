<?php

namespace App\Http\Controllers\Auth\Api;

use App\Events\SmsEvenet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Client;
use App\Cluster;
use App\PasswordReset;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
class AuthController extends Controller
{
    /**
     * Register user via api
     *
     * @return \Illuminate\Http\Response
     */

    // Assingn role permissions tool v2 - v4 
    public function assign_Role(){
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|max:55',
            'phone'    => 'required|unique:users,phone|size:11|regex:/(01)[0-9]{9}/',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'area'     => 'required|exists:clusters,id',
            'address'  => 'required|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        try{
            DB::beginTransaction();

            $otp_code = mt_rand(10000, 99999);
            $data['password'] = bcrypt($request->password);
            $data['otp_code'] = $otp_code;
            $data['ref_code'] = refCode();

            $user = User::create($data);

            $user->assignRole(7);
            $data['user_id'] = $user->id;
            $data['rating'] = 5;
            $data['type'] = 'client';
            
            $client = Client::create($data);
            event(new SmsEvenet($user->phone, "Your Mistri Mama sign up verification code is ".$otp_code.". Do not share it with anyone."));

            DB::commit();
            
            return response()->json(['message' => 'Registration has been completed. Please verify your phone number.','id' => $user->id ], 200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Registration has been failed. Please try again'], 400);
        }
    }

    public function guestRegister(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|unique:users,phone|size:11|regex:/(01)[0-9]{9}/',
            'area'     => 'required|exists:clusters,id',
        ]);

        try{
            DB::beginTransaction();

            $data['name'] = (!empty($request->name)) ? $request->name : 'Name not provided';
            $otp_code = mt_rand(10000, 99999);
            $data['password'] = bcrypt(12345678);
            $data['otp_code'] = $otp_code;
            $data['ref_code'] = refCode();

            $user = User::create($data);

            $user->assignRole(7);
            $data['user_id'] = $user->id;
            $data['rating'] = 5;
            $data['type'] = 'client';
            
            $client = Client::create($data);
            
            event(new SmsEvenet($user->phone, "Your login phone number is: ". $user->phone ." & password is: 12345678. Do not share it with anyone."));
            DB::commit();
            
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ], 200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Registration has been failed. Please try again'], 400);
        }
    }

    /**
     * login via api (passport)
     *
     * @return \Illuminate\Http\Response
     */
    public static function login(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|exists:users,phone|size:11|regex:/(01)[0-9]{9}/',
            'password' => 'required',
        ]);
        
        if (auth()->attempt(['phone' => $data['phone'], 'password' => $data['password'], 'status' => 1])) {
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['user' => new UserResource(auth()->user()), 'access_token' => $token, 'message' => 'Your authentation has been successfull.'], 200);
        }
        return response()->json(['message' => 'Please write down right credentials'], 400);
    }

    public function forgotPassword(Request $request, $type = null)
    {
        $data = $request->validate([
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
        ]);

        $phone = $request->phone;
        $otp_code = mt_rand(10000, 99999);

        $user = User::where(['phone' => $phone])->first();
        
        $check_exists = 'web';
        if($type == 'sp')
        {
            $check_exists = $user->serviceProvider;
        }
        if($type == 'user')
        {
            $check_exists = $user->client;
        }
        
        if(empty($check_exists))
        {
            return response()->json(['message' => 'Your account does not exists.'], 400);
        }

        if(!empty($user))
        {
            PasswordReset::where(['phone' => $phone])->delete();
            PasswordReset::insert([
                'phone' => $phone,
                'otp_code' => $otp_code
            ]);
            event(new SmsEvenet($user->phone, "Your Mistri Mama forgot password verification code is ".$otp_code.". Do not share it with anyone."));
            return response()->json(['message' => 'A code has been sent to your phone number.'], 200);
        }
        return response()->json(['message' => 'Try again or click forgot password to reset it.'], 400);
    }

    public function varifyPasswordOtp(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|exists:password_resets,phone|size:11|regex:/(01)[0-9]{9}/',
        ]);

        $phone = $request->phone;
        $otp_code = $request->otp_code;
        $exists = PasswordReset::where(['phone' => $phone, 'otp_code' => $otp_code])->exists();
        if($exists)
        {
            return response()->json(['message' => 'Verification code verified successfully.'], 200);
        }
        return response()->json(['message' => 'Faild to verified verification code.'], 400);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|exists:users,phone|size:11|regex:/(01)[0-9]{9}/',
            'password' => 'required|confirmed|min:6',
        ]);

        $phone = $request->phone;
        $password = $request->password;

        $changePassword = User::where(['phone' => $phone])->update([
            'password' => bcrypt($password)
        ]);

        if($changePassword)
        {
            return response()->json(['message' => 'Password Change successfull.'], 200);
        }
        return response()->json(['message' => 'Password Change failed.'], 400);
    }

    /**
     * varify phone number via api (passport)
     *
     * @return \Illuminate\Http\Response
     */
    public function varifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp_code' => 'required',
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
            'password' => 'required',
        ]);

        $existsUser = User::where(['phone' => $request->phone, 'otp_code' => $request->otp_code])->first();
        if (!empty($existsUser)) {

            try{
                DB::beginTransaction();
                $existsUser->status = 1;
                $existsUser->phone_verified_at = Carbon::now()->toDateTimeString();
                $existsUser->save();
                
                event(new SmsEvenet($request->phone, "Dear ".$existsUser->name.", thank you for Mistri Mama user registration. Use ".$existsUser->phone." for log in."));

                DB::commit();
                return self::login($request);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['message' => 'Faild to verified verification code'], 400);
            }
            
        } else {
            return response()->json(['message' => 'Faild to verified verification code'], 400);
        }
    }

    // logged user change password use this method
    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);
        
        $password = $request->password;
        $user = User::findOrFail(auth()->user()->id);

        if (Hash::check($request->present_password, $user->password)) { 
            $changePassword = User::where(['id' => auth()->user()->id])->update([
                'password' => bcrypt($password)
            ]);
    
            if($changePassword)
            {
                return response()->json(['message' => 'Password Change successfull.','status' => 1], 200);
            }
            return response()->json(['message' => 'Password change failed.','status' => 0], 200);
         
         } else {
            return response()->json(['message' => 'Old password does not match.','status' => 0], 200);
         }
 
       
    }

    public function check_social_login(Request $request)
    {
        $email = $request->email;
        $client_exists = Client::where('email', $email)->exists();
        if($client_exists)
        {
            try{
                DB::beginTransaction();
                $user = User::where(['email' => $email, 'status' => 1])->first();
                $token = $user->createToken('authToken')->accessToken;
                DB::commit();
                return response()->json(['user' => new UserResource($user), 'access_token' => $token, 'message' => 'Your authentation has been successfull.'], 200);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['message' => 'Login Failed'], 400);
            }
        }
        return response()->json($client_exists);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
        ]);

        $otp_code = mt_rand(10000, 99999);
        $phone = $request->phone;

        try{
            DB::beginTransaction();

            PasswordReset::where(['phone' => $phone])->delete();
            PasswordReset::insert([
                'phone' => $phone,
                'otp_code' => $otp_code
            ]);

            DB::commit();
            
            event(new SmsEvenet($phone, "Your Mistri Mama OTP code is ".$otp_code.". Do not share it with anyone."));
            return response()->json($otp_code, 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Try again send OTP.'.$e], 400);
        }
    }

    public function social_authentication(Request $request)
    {
        $request->validate([
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
        ]);

        $name = $request->name;
        $email = $request->email;
        $phone_no = $request->phone;
        
        $clusters = Cluster::first();
        
        $client_exists = User::where(['phone' => $phone_no, 'status' => 1])->first();
        if(!empty($client_exists))
        {
            User::where('phone', $phone_no)->update([
                'email' => $email
            ]);
            Client::where('phone', $phone_no)->update([
                'email' => $email
            ]);

            if (auth()->loginUsingId($client_exists->id))
            {
                $token = $client_exists->createToken('authToken')->accessToken;
                return response()->json(['user' => new UserResource(auth()->user()), 'access_token' => $token], 200);
            }
            else
            {
                return response()->json(['message' => "Please write down right credentials"], 400);
            }
        }
        else
        {
            try{
                DB::beginTransaction();
    
                $data['name'] = (!empty($name)) ? $name : 'Name not provided';
                $data['phone'] = $phone_no;
                $data['email'] = $email;
                $data['password'] = bcrypt(12345678);
                $data['otp_code'] = mt_rand(10000, 99999);
                $data['ref_code'] = refCode();
                $data['status'] = 1;
    
                $user = User::create($data);
    
                $user->assignRole(7);
                $data['user_id'] = $user->id;
                $data['rating'] = 5;
                $data['type'] = 'client';
                $data['company_name'] = $data['name'];
                $data['area'] = $clusters->id;
                
                $client = Client::create($data);
                DB::commit();
                
                if (auth()->attempt(['phone' => $data['phone'], 'password' => $data['password'], 'status' => 1])) {
                    $token = auth()->user()->createToken('authToken')->accessToken;
                    return response()->json(['user' => new UserResource(auth()->user()), 'access_token' => $token, 'message' => 'Your authentation has been successfull.'], 200);
                }
                return response()->json(['message' => 'Please write down right credentials'], 400);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['message' => "Please write down right credentials"], 400);
            }
        }
    }
    
    public function checkExistsUser(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
            'type'    => 'required',
        ]);

        $phone = $request->phone;
        $type = $request->type;
        $user = User::where(['phone' => $phone])->first();
        
        $response = [];
        if(!empty($user))
        {
            if($type == 'sp')
            {
                $check_exists = $user->serviceProvider;
                $response = [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'type' => 'service_provider',
                ];
            }
            if($type == 'user')
            {
                $check_exists = $user->client;
                $response = [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'type' => 'user',
                ];
            }
            
            if(empty($check_exists))
            {
                return response()->json(['message' => 'Mobile no can not found.'], 400);
            }
            return response()->json($response, 200);
        }
        return response()->json(['message' => 'Mobile no can not found.'], 400);
    }

    public function requestOtp(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
            'type'    => 'required',
        ]);

        $phone = $request->phone;
        $app_code = $request->app_code;
        $type = $request->input('type');
        $otp_code = mt_rand(10000, 99999);

        $user = User::where(['phone' => $phone])->first();
        try{
            DB::beginTransaction();

            if(!empty($user))
            {
                if($type == 'sp')
                {
                    $check_exists = $user->serviceProvider;
                }
                if($type == 'user')
                {
                    $check_exists = $user->client;
                }
                
                if(empty($check_exists))
                {
                    return response()->json(['message' => 'Sorry, You cannot login with this number.'], 400);
                }

                PasswordReset::where(['phone' => $phone])->delete();
                PasswordReset::insert([
                    'phone' => $phone,
                    'otp_code' => $otp_code
                ]);

                DB::commit();
                
                event(new SmsEvenet($user->phone, "Your Mistri Mama OTP code is ".$otp_code.". Do not share it with anyone. APPCODE ".$app_code.""));
                return response()->json(['message' => 'A code has been sent to your phone number.'], 200);
            }
            return response()->json(['message' => 'Try again send OTP.'], 400);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Try again send OTP.'], 400);
        }
    }

    public function requestOta(Request $request)
    {
        $data = $request->validate([
            'otp_code' => 'required',
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
        ]);

        $phone = $request->phone;
        $otp_code = $request->otp_code;

        $existsUser = PasswordReset::where(['phone' => $phone, 'otp_code' => $otp_code])->first();
        if (!empty($existsUser)) {

            try{
                DB::beginTransaction();
                $user = User::where(['phone' => $phone])->first();
                $token = $user->createToken('authToken')->accessToken;
                PasswordReset::where(['phone' => $phone])->delete();
                DB::commit();
                return response()->json(['user' => new UserResource($user), 'access_token' => $token, 'message' => 'Your authentation has been successfull.'], 200);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['message' => 'Faild to verified otp code'], 400);
            }
        } else {
            return response()->json(['message' => 'OTP code has been expaired'], 400);
        }
    }
}