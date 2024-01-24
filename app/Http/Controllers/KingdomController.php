<?php

namespace App\Http\Controllers;

use App\Models\Kingdom;
use Illuminate\Http\Request;

class KingdomController extends Controller
{
    public function index(){
        $kingdoms = Kingdom::all();

        return response()->json([
            "message" => "items retrieved successfully",
            "items" => $kingdoms
        ]);
    }
}
