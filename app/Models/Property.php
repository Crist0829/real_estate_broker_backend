<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function prices(){
        return $this->hasMany(PropertyPrice::class, 'property_id');
    }

    public function images(){
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

}

