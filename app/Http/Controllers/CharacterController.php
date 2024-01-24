<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $characters = $this->params($request, false);

        return response()->json([
            "message" => "items retrieved successfully",
            "items" => $characters
        ]);
    }

    public function paginated()
    {
        $characters = $this->params(request(), true);

        return response()->json([
            "message" => "items retrieved successfully",
            "pagination" => $characters
        ]);
    }

    public function params($request, $paginated = false, $perPage = 10)
    {
        $query = Character::query();

        // if request has includeKingdom query param
        if ($request->has('includeKingdom')) {
            $query->with('kingdom');
        }

        // if request has includeKingdom query param
        if ($request->has('includeEpisodes')) {
            $query->with('episodes');
        }

        if ($paginated) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
