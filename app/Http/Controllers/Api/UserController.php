<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;




class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|email|max:191',
            'password' => 'required',
        ]);
        if ($validate->fails()){
            return response()->json([
                'validation_error' => $validate->messages()
            ]);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => __('invalid Email'),
            ]);
        }

        $user = User::select('id', 'email', 'password')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('Invalid Email or Password')
            ]);
        } else {
//            $token = $user->createToken(Str::slug(get_static_option('Project247', 'ecommerce')) . 'api_keys')->plainTextToken;
            $token = $user->createToken('dsfsdf')->plainTextToken;
            return response()->json([
                'users' => $user,
                'token' => $token,
            ]);
        }
    }



    //register api
    public function register(Request $request)
    {

        $validate = Validator::make($request->all(),[
            'full_name' => 'required|max:191',
            'email' => 'required|email|unique:users|max:191',
            'username' => 'required|unique:users|max:191',
            'phone' => 'required|unique:users|max:191',
            'password' => 'required|min:6|max:191',
            'country_id' => 'required',
            'country_code' => 'required',
            'state_id' => 'nullable',
            'terms_conditions' => 'required',
        ]);
        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ]);
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => __('invalid Email'),
            ]);
        }

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'country_code' => $request->country_code,
            'country' => $request->country_id,
            'state' => $request->state_id,
        ]);
        if (!is_null($user)) {
//            $token = $user->createToken(Str::slug(get_static_option('site_title', 'zaika')) . 'api_keys')->plainTextToken;
            $token = $user->createToken('fldksSDFSDf')->plainTextToken;
            return response()->json([
                'users' => $user,
                'token' => $token,
            ]);
        }
        return response()->json([
            'message' => __('Something Went Wrong'),
        ]);
    }



}
