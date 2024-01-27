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

    public function paginated(Request $request){

        $validator = Validator::make($request->all(), [
            'perPage' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "validation error",
                "error" => "perPage must be an integer"
            ], 404);
        }

        $query = Episode::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        

        if($request->has('perPage')) {
            $perPage = $request->query('perPage');
            $episodes = $queryWithRelationships->paginate($perPage);
        } else {
            $episodes = $queryWithRelationships->paginate(10);
        }

        return response()->json([
            "message" => "items retrieved successfully",
            'pagination' => $episodes
        ]);
    }

    private function idIdsValid($ids){
       return preg_match('/^\d+(,\d+)*(,)?$/', $ids);
    }

    public function show(Request $request, $ids){
        
        $isIdsValid = $this->idIdsValid($ids);

        if (!$isIdsValid) {
            return response()->json([
                "message" => "validation error",
                "error" => "ids must be one or an array of integers, example: 1 or 1,2,3"
            ], 404);
        }

        
        $query = Episode::query();
        $idsArray = explode(',', $ids);
        $idsCount = count($idsArray);

        if($idsCount === 1){

            $id = $ids[0];
            $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
            $episode = $queryWithRelationships->find($id);
            
            if(!$episode) return response()->json([
                "message" => "item not found",
                "error" => "id is not associated with any episode"
            ], 404);
            

            return response()->json([
                "message" => "ite retrieved successfully",
                "item" => $episode
            ]);
        }

        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $episodes = $queryWithRelationships->findMany($idsArray);


        return response()->json([
            "message" => "item retrieved successfully",
            "items" => $episodes
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
