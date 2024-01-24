<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(){
        $episodes = $this->params(request(), false);

        return response()->json([
            "message" => "items retrieved successfully",
            'items' => $episodes
        ]);
    }

    public function paginated(){
        $episodes = $this->params(request(), true);

        return response()->json([
            "message" => "items retrieved successfully",
            'pagination' => $episodes
        ]);
    }

    public function params($request, $paginated = false, $perPage = 10){
        $episodes = Episode::query();

        if($request->has('includeCharacters')){
            $episodes->with('characters');
        }

        if($paginated){
            return $episodes->paginate($perPage);
        }

        return $episodes->get();
    }
}
