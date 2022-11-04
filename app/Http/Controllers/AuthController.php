<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  /**
   * Create user
   *
   * @param  [string] name
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @return [string] msg
   */
  public function register(Request $request)
  {
    
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string',
                'c_password' => 'required|same:password',
                'role' => 'exists:roles,slug|required|string',
                'phone_number' => 'required_if:role,customer',
                'gender' => 'required_if:role,customer',
            ]);
 

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }

    

    $user = new User([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password)
    ]);
    if ($user->save()) {
      $user->assignRole($request->role);
      return response([ 'msg' => 'Successfully created user!'], 200);
    } else {
      return response(['error' => 'Provide proper details'], 401);
    }
  }

  /**
   * Login user and create token
   *
   * @param  [string] email
   * @param  [string] password
   * @param  [boolean] remember_me
   * @return [string] access_token
   * @return [string] token_type
   * @return [string] expires_at
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'msg' => $validator->errors()->first(),
            'response' => $request->all()
        ]);
    }

    $type = $request->input('type');
    $email = $request->input('email');
    $password = $request->input('password');

    $user = User::with(['roles'])->where('email', '=', $email)->first();

    if(!$user){
      return response()->json([ 'msg' => 'Email Incorrect'], 401);
    }

    if($type != $user->roles->last()->slug){
      return response()->json([ 'msg' => 'Incorrect credentials'], 401);
    }

    if (!Hash::check($password, $user->password)) {
      return response()->json([ 'msg' => 'Password Incorrect'], 401);
    }   

    $tokenResult = $user->createToken(rand(10, 99999));
    $token = $tokenResult->token;
    if ($request->remember_me)
        $token->expires_at = Carbon::now()->addWeeks(1);
     $token->save();
     return response()->json([
         'msg' => 'Login Successfull',
         'access_token' => $tokenResult->accessToken,
         'token_type' => 'Bearer',
         'expires_at' => Carbon::parse(
             $tokenResult->token->expires_at
          )->toDateTimeString(),
          'user' => $user
      ], 200);
  }

  /**
   * Get the authenticated User
   *
   * @return [json] user object
   */
  public function user(Request $request)
  {
    $user = $request->user();
    return response()->json($user);
  }

  /**
   * Logout user (Revoke the token)
   *
   * @return [string] msg
   */
  public function logout(Request $request)
  {
    try{
      $request->user()->token()->revoke();
      return response()->json([ 'msg' => 'Successfully logged out' ], 200);
    } catch(Exception $e){
      return response()->json([ 'msg' => $e->getMessage()], 200);
    }

  }

}
