<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EpisodeController extends Controller
{
    public function index(Request $request){
        $query = Episode::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $episodes = $queryWithRelationships->get();

        return response()->json([
            "message" => "items retrieved successfully",
            'items' => $episodes
        ]);
    }

    public function paginated(Request $request, $perPage = 10){
        $query = Episode::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $episodes = $queryWithRelationships->paginate($perPage);

        return response()->json([
            "message" => "items retrieved successfully",
            'pagination' => $episodes
        ]);
    }

    public function show(Request $request, $id){
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "validation error",
                "error" => "id is required and must be an integer"
            ], 404);
        }

        $query = Episode::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

        $episode = $queryWithRelationships->find($id);

        if(!$episode) return response()->json([
            "message" => "item not found",
            "error" => "id is not associated with any episode"
        ], 404);

        return response()->json([
            "message" => "item retrieved successfully",
            "item" => $episode
        ]);
    }

    public function showBySlug(Request $request, $slug){
        $query = Episode::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

        $episode = $queryWithRelationships->where('slug', $slug)->first();

        if(!$episode) return response()->json([
            "message" => "item not found",
            "error" => "slug is not associated with any episode"
        ], 404);

        return response()->json([
            "message" => "item retrieved successfully",
            "item" => $episode
        ]);
    }

    private function injectRelationshipToQuery(Request $request, EloquentBuilder $query){
        if($request->has('includeCharacters')){
            $query->with('characters');
        }

        return $query;
    }
}
