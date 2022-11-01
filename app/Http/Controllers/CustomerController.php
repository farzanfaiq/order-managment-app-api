<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
//     protected function guard()
// {
//     return Auth::guard('customer');
// }

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
                'email' => 'required|string|email|unique:customers',
                'password' => 'required|string|',
                'c_password' => 'required|same:password',
                'phone_number' => 'required|string',
                'gender' => 'required|string',
            ]);
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }

    $customer = new Customer([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
      'phone_number' => $request->phone_number,
      'gender' => $request->gender,
    ]);
    if ($customer->save()) {
      return response([
        'msg' => 'Successfully created customer!'
      ], 201);
    } else {
      return response(['error' => 'Provide proper details'], 401);
    }
  }

  /**
   * Login customer and create token
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
      
    $credentials = request(['email', 'password']);

     if(!auth('customer')->attempt($credentials)){
         return response()->json([
            'msg' => 'Login credentials are incorrect', 
         ],401);
}

     $customer = auth('customer')->user();
     $tokenResult = $customer->createToken('Personal Access Token');
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
          'customer' => $customer
      ], 200);
  }

  /**
   * Get the authenticated User
   *
   * @return [json] user object
   */
  public function customer(Request $request)
  {
    $customer = $request->guard('customer')->user();
    return response()->json($customer);
  }

  /**
   * Logout user (Revoke the token)
   *
   * @return [string] msg
   */
  public function logout(Request $request)
  {
    try{
      $this->guard('customer')->user()->token()->revoke();
    return response()->json([
      'msg' => 'Successfully logged out'
    ], 200);
    } catch(Exception $e){
      return response()->json([
      'msg' => $e->getMessage()
    ], 200);
    }

  }
}