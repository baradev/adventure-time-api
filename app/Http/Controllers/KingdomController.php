<?php

namespace App\Http\Controllers;

use App\Models\Kingdom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class KingdomController extends Controller
{
    public function index(Request $request)
    {
        $query = Kingdom::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $kingdoms = $queryWithRelationships->get();

        return response()->json([
            "message" => "items retrieved successfully",
            'items' => $kingdoms
        ]);
    }

    public function paginated(Request $request, $perPage = 10)
    {
        $query = Kingdom::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);
        $kingdoms = $queryWithRelationships->paginate($perPage || 10);
        return response()->json([
            "message" => "items paginated retrieved successfully",
            'pagination' => $kingdoms
        ]);
    }

    public function show(Request $request, $id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "validation error",
                "error" => "id is required and must be an integer"
            ], 404);
        }

        $query = Kingdom::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

        $kingdom = $queryWithRelationships->find($id);

        if(!$kingdom) return response()->json([
            "message" => "item not found",
            "error" => "id is not associated with any kingdom"
        ], 404);

        return response()->json([
            "message" => "item retrieved successfully",
            'item' => $kingdom
        ]);

    }

    public function showBySlug(Request $request, $slug)
    {

        $validator = Validator::make(['slug' => $slug], [
            'slug' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "validation error",
                "error" => "slug is required"
            ], 404);
        }

        $query = Kingdom::query();
        $queryWithRelationships = $this->injectRelationshipToQuery($request, $query);

        $kingdom = $queryWithRelationships->where('slug', $slug)->first();

        if(!$kingdom) return response()->json([
            "message" => "item not found",
            "error" => "slug is not associated with any kingdom"
        ], 404);

        return response()->json([
            "message" => "item retrieved successfully",
            'item' => $kingdom
        ]);
    }


    private function injectRelationshipToQuery(Request $request, EloquentBuilder $query)
    {
        if ($request->has('includeCharacters')) {
            $query->with('characters');
        }   
        return $query;
    }
}
