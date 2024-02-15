<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::with(['prices'])->where('user_id', auth()->user()->id)->paginate(8);
        return response()->json([
            'properties' => $properties
        ]);
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAll(Request $request)
    {
        $properties = Property::with(['prices'])->paginate(8);
        return response()->json([
            'properties' => $properties
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string'],
            'floors' => ['required', 'numeric'],
            'livingrooms' => ['required', 'numeric'],
            'bedrooms' => ['required', 'numeric'],
            'kitchens' => ['required', 'numeric'],
            'bathrooms' => ['required', 'numeric'],
            'garage' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'datos incorrectos', 
                'errores' => $validator->errors()->toArray()
            ]);
        }


        $property = new Property();
        $property->name = $request->name;
        $property->description = $request->description;
        $property->location = $request->location;
        $property->status = $request->status;
        $property->floors = $request->floors;
        $property->bedrooms = $request->bedrooms;
        $property->livingrooms = $request->livingrooms;
        $property->bathrooms = $request->bathrooms;
        $property->kitchens = $request->ketchens;
        $property->garage = $request->garage;
        $property->user_id = auth()->user()->id;
        $property->save();

        return response()->json([
            'message' => 'El inmueble se agregó correctamente',
        ]);

    }   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);
        return response()->json(
            ['property' => $property]
        );
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
        //
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
