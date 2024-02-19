<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyCalification;
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
        
        $properties = Property::with(['prices', 'images', 'califications', 'user']);        
        $filtersWhere = $request->only(['bedrooms', 'bathrooms', 'kitchens', 'floors', 'livingrooms']);

        if($request->deleted){
            $properties = $properties->onlyTrashed();
        }

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

        

        $properties = $properties->where('user_id', auth()->user()->id);
        $properties = $properties->paginate($request->paginate ?? 8);
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
        $properties = Property::with(['prices', 'images', 'califications', 'user']);        
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
                'message' => 'Incorrect data', 
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
        $property = Property::with(['images', 'prices', 'califications', 'user'])->findOrFail($id);
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


    
    /**
     * Add price
     * 
     *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $price->type = $request->type;
        $price->save();

        return response()->json([
            'message' => 'The price was added correctly', 
        ]);


    }


    /**
     * Add calification
     * 
     *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addCalification(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'calification' => ['required', 'numeric'],
        ]);



        if ($validator->fails()) {
            return response()->json([
                'message' => 'Incorrect data', 
                'errores' => $validator->errors()->toArray()
            ], 422);
        }

        if($request->calification > 5 || $request->calification <  1){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' => "The rating must be a number between 1 and 5"
            ], 422);
        }

        $property = Property::findOrFail($id);
        $propertyCalification = PropertyCalification::where('user_id', auth()->user()->id)->where('property_id', $id)->first();
        if(!$propertyCalification) $propertyCalification = new PropertyCalification();
        $propertyCalification->user_id = auth()->user()->id;
        $propertyCalification->property_id = $id;
        $propertyCalification->calification = $request->calification;
        $propertyCalification->save();

        return response()->json($propertyCalification);
    
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

        if($property->trashed()){
            $property->forceDelete();
            return response()->json([
                'message' => 'The property was successfully permanently deleted', 
            ]);
        }

        $property->delete();

        return response()->json([
            'message' => 'The property was successfully deleted', 
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePrice($id){

        $propertyPrice = PropertyPrice::findOrFail($id);
        $property = Property::findOrFail($propertyPrice->property_id);
        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }
        $propertyPrice->delete();
        return response()->json([
            'message' => 'The price was successfully deleted', 
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteImage($id){

        $propertyImage = PropertyImage::findOrFail($id);
        $property = Property::findOrFail($propertyImage->property_id);
        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }
        $path = 'properties/'. $propertyImage->name;
        Storage::disk('public')->delete($path);
        $propertyImage->delete();
        return response()->json([
            'message' => 'The price was successfully deleted', 
        ]);

    }



    /**
     * Restore afete a soft delete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id){

        $property = Property::withTrashed()->where('id', $id)->first();

        if($property->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Incorrect data', 
                'error' =>  'The property was not charged by you'
            ], 403);
        }


        if($property && $property->trashed()){
            $property->restore();
            return response()->json([
                'message' => 'The property was succesfully restored'
            ]);
        }

        return response()->json([
            'message'=> 'Incorrect Data',
            'Error' => 'The property wasent deleted'
        ], 422);

    }


}
