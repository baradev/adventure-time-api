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
        $queryWithFilters = $this->injectFilterParms($request, $queryWithRelationships);
        $characters = $queryWithFilters->get();

        return response()->json([
            "message" => "items retrieved successfully",
            "items" => $characters,
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
        $queryWithFilters = $this->injectFilterParms($request, $queryWithRelationships);

        if ($request->has('perPage')) {
            $perPage = $request->query('perPage');
            $characters = $queryWithFilters->paginate($perPage);
        } else {
            $characters = $queryWithFilters->paginate(10);
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

        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $characters = $queryWithRelationships->findMany($idArray);

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

    private function injectFilterParms(Request $request, EloquentBuilder $query){

        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }        
        
        if ($request->has('full_name')){
            $query->where('full_name', 'LIKE', '%' . $request->query('full_name') . '%');
        }
        if ($request->has('specie')){
            $query->where('specie', 'LIKE', '%' . $request->query('specie') . '%');
        }

        return $query;
    }
}
