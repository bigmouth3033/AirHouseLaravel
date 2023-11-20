<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\PropertyAmenity;
use Illuminate\Http\Request;

class AmenityControllerCopy extends Controller
{
    public function index(){
        $amentities = Amenity::all();
        foreach ($amentities as $amenity){
            if ($amenity->icon_image!= null) {
                $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
            }
            $amenity->icon_image = null;
        }
        return view("Amenity")->with("data", $amentities);
    }
}
