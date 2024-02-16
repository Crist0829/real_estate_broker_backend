<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $properties = $request->eliminated ? Property::onlyTrashed()->with(['prices', 'images', 'user']) : Property::with(['prices', 'images', 'user']);
        $properties = $properties->where('user_id', auth()->user()->id)->paginate($request->paginate ?? 5);
        
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
        $properties = Property::with(['prices', 'images', 'user']);        
        $filtersWhere = $request->only(['bedrooms', 'bathrooms', 'kitchens', 'floors', 'livingrooms']);

        foreach($filtersWhere as $key => $value){
            if($request->$key && $request->$key != null){
                $properties = $properties->where($key, $value);
            }
        }

        if(isset($request->garage)){
            $properties = $properties->where('garage', $request->garage);
        }
            

        if($request->type){
            $properties = $properties->whereHas('prices', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }



        $properties = $properties->paginate($request->paginate ?? 8);
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
            'status' => ['required', 'in:sold,rented,available'],
            'size' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'datos incorrectos', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }


        $property = new Property();
        $property->name = $request->name;
        $property->description = $request->description;
        $property->size = $request->size;
        $property->location = $request->location;
        $property->status = $request->status;
        $property->floors = $request->floors;
        $property->bedrooms = $request->bedrooms;
        $property->livingrooms = $request->livingrooms;
        $property->bathrooms = $request->bathrooms;
        $property->kitchens = $request->kitchens;
        $property->size = $request->size;
        $property->garage = $request->garage;
        $property->user_id = auth()->user()->id;
        $property->save();

        return response()->json([
            'message' => 'The property was added correctly',
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
        $property = Property::with(['images', 'prices', 'user'])->findOrFail($id);
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
        $property = Property::findORFail($id);

        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }

        $count = 0;

        $propertyFields = $request->only([
            'livingromms', 'bedrooms', 'bathrooms', 
            'kitchens', 'garage', 'size', 'floors', 
            'status', 'name', 'description', 'location'
        ]);

        foreach($propertyFields as $key => $value){
            if($property->$key != $request->$key){
                $property->$key = $value;
                $count ++;
            }
        }

        if($count){
            $property->save();
            return response()->json([
                'message' => 'The information was changed successfully', 
            ]);
        }

        return response()->json([
            'message' => 'No modification was made', 
        ], 204);

    }


    /**
     * Upload image
     * 
     *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function uploadImage(Request $request, $id){

        $property = Property::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Incorrect data', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }

        $userId = auth()->user()->id;
        $fileName = $userId . '_imagen_' . uniqid() . '.' . $request->file('image')->extension();
        Storage::putFileAs('public/properties', $request->file('image'), $fileName);
        $image = new PropertyImage();
        $image->name = $fileName;
        $image->description = $request->description;
        $image->url = asset('storage/properties/' . $fileName);
        $image->property_id = $id;
        $image->save();

        return response()->json([
            'message' => 'Image uploaded successfully', 
        ]);


    }

    public function addPrice(Request $request, $id){

        $property = Property::findOrFail($id);

        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string'],
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'type' => ['required', 'in:rent,sale'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Incorrect data', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        $price = new PropertyPrice();
        $price->price = $request->price;
        $price->description = $request->description;
        $price->name = $request->name;
        $price->property_id = $id;
        $price->save();

        return response()->json([
            'message' => 'The price was added correctly', 
        ]);


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $property = Property::findOrFail($id);

        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }

        $property->delete();

        return response()->json([
            'message' => 'The resource was successfully deleted', 
        ]);

    }
}
