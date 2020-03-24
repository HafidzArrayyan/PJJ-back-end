<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    //tambahkan ini
    public function login(Request $request){
        $credentials = $request->only('email','password');
        try{
            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error'=>'inavlid_credentials'], 400);
                }
            } catch (Exception $e){
                return response()->json(['error'=>'could_not_create_token'], 500);
            }
            return response()->json(compact('token'));
        }
        public function register(Request $request){
            $validator = Validator::make($request->all(), [
                'name'=>'required|string|max:250',
                'email'=>'required|string|max:250|unique:users',
                'password'=>'required|string|min:8',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }
            $user = User::create([
                'name'=>$request->get('name'),
                'email'=>$request->get('email'),
                'password'=>Hash::make($request->get('password')),
            ]);
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user','token'), 201);
        }
        public function getAuthenticatedUser(){
            try{
                if(! $user = JWTAuth::parseToken()->authenticate()){
                    return response()->json(['user_not_found'],404);
                    }
                } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
                    return response()->json(['Token_expired'],$e->getStatusCode());
                }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                    return response()->json(['Token_invalid'],$e->getStatusCode());
                }catch (Tymon\JWTAuth\Exceptions\JWTException $e){
                    return response()->json(['Token_absent'],$e->getStatusCode());
                }
                return response()->json(compact('user'));
    }
}
