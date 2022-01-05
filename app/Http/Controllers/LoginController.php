<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
                'email'=>'required|string',
                'password'=>'required|string'
            ]);
        if(Auth::attempt($login)){
            $token = Auth::user()->createToken('AuthToken')->accessToken;
           return response()->json(['user'=>Auth::user(),'accessToken'=>$token]);
        }
        else{
            return response()->json(['message'=> 'Authentication failed']);
        }
    }


    public function register(Request $request)
    {
        $register = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);
        if ( !$register ) {
            return response()->json(['message'=>'Validation Failed']);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('AuthToken')->accessToken;
        return response()->json(['message'=>'Registration Sucessfull','token'=>$token]  );
    }
   public function details()
   {
       $user = User::all();
       return response()->json(['message'=>'Sucessfull', 'user'=> $user]);
   }
}
