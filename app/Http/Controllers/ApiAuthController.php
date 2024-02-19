<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;


class ApiAuthController extends Controller
{
    public function Login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Incorrect data', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json( [
                'message' => 'email o contraseña incorrecta'
            ], 422);
        }
    
        $user = User::where('email', $request->email)->first();
        $user->tokens()->where('name', 'login_token')->delete();
        $token = $user->createToken('login_token');
        
        return response()->json([
            'message' => 'logged succesfully', 
            'token' =>  $token->plainTextToken
        ]);
    
    }

    public function logout(){
        $user = User::where('email', auth()->user()->email)->first();
        $user->tokens()->where('name', 'login_token')->delete();
        return response()->json([
            'message' => 'Token was removed successfully'
        ], 204 );
    }


    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Incorrect data', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        $user = New User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->save();

        $roleUser = new RoleUser();
        $roleUser->user_id = $user->id;
        $roleUser->role_id = Role::AGENT;
        $roleUser->save();


        $user->tokens()->where('name', 'login_token')->delete();
        $token = $user->createToken('login_token');
        
        return response()->json([
            'message' => 'logged succesfully', 
            'token' =>  $token->plainTextToken
        ]);
    }


}
