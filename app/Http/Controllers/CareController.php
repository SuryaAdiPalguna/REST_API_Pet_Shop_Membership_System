<?php

namespace App\Http\Controllers;

use App\Models\Care;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class CareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cares = QueryBuilder::for(Care::class)->allowedFilters([AllowedFilter::scope('search')])->paginate(10)->appends(request()->query());
            return response()->json([
                'success' => true,
                'cares' => $cares->items(),
                'page' => [
                    'size' => $cares->perPage(),
                    'total_elements' => $cares->total(),
                    'total_pages' => $cares->lastPage(),
                    'number' => $cares->currentPage(),
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
                'description' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Care::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'New care has been stored!',
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
    public function show(Care $care)
    {
        try {
            return response()->json([
                'success' => true,
                'care' => $care,
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
    public function edit(Care $care)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Care $care)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['sometimes', 'string', 'min:4', 'max:255'],
                'description' => ['sometimes', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $care->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Care has been updated!',
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
    public function destroy(Care $care)
    {
        try {
            $care->delete();
            return response()->json([
                'success' => true,
                'message' => 'Care has been deleted!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }
}
