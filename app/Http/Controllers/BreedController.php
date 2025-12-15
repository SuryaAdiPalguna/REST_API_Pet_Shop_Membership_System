<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $breeds = QueryBuilder::for(Breed::class)->allowedFilters([AllowedFilter::scope('search')])->paginate(10)->appends(request()->query());
            return response()->json([
                'success' => true,
                'breeds' => $breeds->items(),
                'page' => [
                    'size' => $breeds->perPage(),
                    'total_elements' => $breeds->total(),
                    'total_pages' => $breeds->lastPage(),
                    'number' => $breeds->currentPage(),
                ],
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:4', 'max:255'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Breed::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'New breed has been stored!',
            ], 201);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Breed $breed)
    {
        try {
            return response()->json([
                'success' => true,
                'breed' => $breed,
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breed $breed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Breed $breed)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['sometimes', 'string', 'min:4', 'max:255'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $breed->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Breed has been updated!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breed $breed)
    {
        try {
            $breed->delete();
            return response()->json([
                'success' => true,
                'message' => 'Breed has been deleted!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }
}
