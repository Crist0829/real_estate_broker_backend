<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if($user->id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'datos incorrectos', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        $count = 0;

        if($request->name && $request->name != $user->name){
            $user->name = $request->name;
            $count ++;
        }

        if($request->email && $request->email != $user->email){
            $user->email = $request->email;
            $count ++;
        }

        if($count){
            $user->save();
            return response()->json([
                'message' => 'The information was changed successfully', 
            ]);
        }

        return response()->json([], 204);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if($user->id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Incorrect data', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        if ($user && Hash::check($request->old_password, $user->password)) {

            if(Hash::check($request->password, $user->password)){
                return response()->json([], 204);
            }
    
            $user->password =  Hash::make($request->password);
            $user->save();
            return response()->json([
                'message' => 'The information was changed successfully', 
            ]);
        }

        

        

       
        return response()->json([
            'message' => 'Incorrect data', 
            'error' => 'The old password is incorrect'
        ], 403);

    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
