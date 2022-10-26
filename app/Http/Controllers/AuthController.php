<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

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
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|string|email|unique:users',
      'password' => 'required|string|',
      'c_password' => 'required|same:password',
    ]);

    $user = new User([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password)
    ]);
    if ($user->save()) {
      return response([
        'msg' => 'Successfully created user!'
      ], 201);
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
    $request->validate([
             'email' => 'required|string|email',
             'password' => 'required|string'
           ]);

      
    $credentials = request(['email', 'password']);


     if(!Auth::attempt($credentials))
         return response()->json([
            'msg' => 'Login Failed'
         ],401);


     $user = $request->user();
     $tokenResult = $user->createToken('Personal Access Token');
     $token = $tokenResult->token;
     if ($request->remember_me)
         $token->expires_at = Carbon::now()->addWeeks(1);
     $token->save();
     return response()->json([
         'msg' => 'Login Success',
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
    $request->user()->token()->revoke();
    return response()->json([
      'msg' => 'Successfully logged out'
    ], 200);
  }

}
