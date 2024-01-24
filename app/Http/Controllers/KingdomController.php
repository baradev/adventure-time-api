<?php

namespace App\Http\Controllers;

use App\Models\Kingdom;
use Illuminate\Http\Request;

class KingdomController extends Controller
{
    public function index(){
        $kingdoms = $this->params(request(), false);

        return response()->json([
            "message" => "items retrieved successfully",
            'items' => $kingdoms
        ]);
    }

    public function paginated(){
        $kingdoms = $this->params(request(), true);

        return response()->json([
            "message" => "items retrieved successfully",
            'pagination' => $kingdoms
        ]);
    }

    public function params($request, $paginated = false, $perPage = 10){
        $kingdoms = Kingdom::query();

        if($request->has('includeCharacters')){
            $kingdoms->with('characters');
        }


        if($paginated){
            return $kingdoms->paginate($perPage);
        }

        return $kingdoms->get();
    }
}
