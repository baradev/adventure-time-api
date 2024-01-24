<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index(){
        $characters = Character::all();

        return response()->json([
            "message" => "items retrieved successfully",
            "items" => $characters
        ]);
    }
}
