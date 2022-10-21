<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{
  User,
  Roles,
  Permissions
};

class AuthController extends Controller
{
  /**
   * Create user
   *
   * @param  [string] name
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @return [string] message
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
        'message' => 'Successfully created user!'
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


      if(auth()->check()){
          return response()->json([
          'msg' =>'already login'
        ], 200);
      }


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
      ], 200);
  }

  /**
   * Get the authenticated User
   *
   * @return [json] user object
   */
  public function user(Request $request)
  {
    // if (!Auth::user()->can('user')) abort(403);
    $user = $request->user();
    return response($user);
  }

  /**
   * Logout user (Revoke the token)
   *
   * @return [string] message
   */
  public function logout(Request $request)
  {
    $request->user()->token()->revoke();
    return response([
      'message' => 'Successfully logged out'
    ], 200);
  }

  public function userpermission(Request $request, $id)
  {
    // if (!Auth::user()->can('user-permission')) abort(403);
    $request->validate([
      'slug' => 'required|array'
    ]);
    $user = User::find($id);

    $user->permissions()->detach();
    foreach ($request->slug as $slug) {
      $permission = Permissions::where('slug', $slug)->first();
      $user->permissions()->attach($permission);
    }

    return response(
      [
        'message' => 'Successfully added permission!'
      ],
      200
    );
  }

  public function userrole(Request $request, $id)
  {
    // if (!Auth::user()->can('user-role')) abort(403);
    $request->validate([
      'slug' => 'required|array'
    ]);
    $user = User::find($id);

    foreach ($request->slug as $slug) {
      $role = Roles::where('slug', $slug)->first();
      $user->roles()->attach($role);
    }

    return response(
      [
        'message' => 'Successfully added roles!'
      ],
      200
    );
  }
}
