<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowCharacterRequest;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $query = Character::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $characters = $queryWithRelationships->get();

        return response()->json([
            "message" => "items retrieved successfully",
            "items" => $characters
        ]);
    }

    public function paginated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perPage' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "validation error",
                "error" => "perPage must be an integer"
            ], 404);
        }

        $query = Character::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

        if ($request->has('perPage')) {
            $perPage = $request->query('perPage');
            $characters = $queryWithRelationships->paginate($perPage);
        } else {
            $characters = $queryWithRelationships->paginate(10);
        }
        return response()->json([
            "message" => "items retrieved successfully",
            "pagination" => $characters
        ]);
    }

    private function isIdsValid($ids)
    {
        return preg_match('/^\d+(,\d+)*(,)?$/', $ids);
    }

    public function getMultipleCharactersByIds($ids)
    {
    }

    public function getCharacterById()
    {
    }

    public function show(Request $request, $ids)
    {
        if ($this->isIdsValid($ids) === 0) {
            return response()->json([
                "message" => "validation error",
                "error" => "ids must be one or an array of integers, example: 1 or 1,2,3"
            ], 404);
        }

        $query = Character::query();

        $idArray = explode(',', $ids);
        $idsCount = count($idArray);

        if ($idsCount == 1) {
            $query->find($idArray[0]);

            $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

            $character = $queryWithRelationships->find($idArray[0]);

            if (!$character) return response()->json([
                "message" => "item not found",
                "error" => "id is not associated with any character"
            ], 404);

            return response()->json([
                "message" => "item retrieved successfully",
                "item" => $character
            ]);
        }

        $query->whereIn('id', $idArray);
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $characters = $queryWithRelationships->get();



        return response()->json([
            "message" => "items retrieved successfully",
            "items" => $characters
        ]);
    }

    public function showBySlug(Request $request, $slug)
    {
        $query = Character::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

        $character = $queryWithRelationships->where('slug', $slug)->first();

        if (!$character) return response()->json([
            "message" => "item not found",
            "error" => "slug is not associated with any character"
        ], 404);

        return response()->json([
            "message" => "item retrieved successfully",
            "item" => $character
        ]);
    }


    private function injectRelationshipToQuery(Request $request, EloquentBuilder $query)
    {
        // if request has includeKingdom query param
        if ($request->has('includeKingdom')) {
            $query->with('kingdom');
        }

        // if request has includeKingdom query param
        if ($request->has('includeEpisodes')) {
            $query->with('episodes');
        }

        return $query;
    }
}
