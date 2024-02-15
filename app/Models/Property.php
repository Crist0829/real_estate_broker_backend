<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;


    public function prices(){
        return $this->hasMany(PropertyPrice::class, 'property_id');
    }

    public function images(){
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

}

