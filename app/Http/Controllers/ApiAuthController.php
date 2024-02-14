<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function Login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'datos incorrectos', 
                'errores' => $validator->errors()->toArray()
            ]);
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
            'message' => 'El token se eliminó correctamente'
        ], 204 );
    }


}
