<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\PropertyAmenity;
use Illuminate\Http\Request;

class AmenityControllerCopy extends Controller
{
    public function index(){
        $amentities = Amenity::all();
        return view("Amenity")->with("data", $amentities);
    }
}
