<?php

namespace App\Http\Controllers;

use App\Models\Adopt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class AdoptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $adopts = QueryBuilder::for(Adopt::class)->allowedFilters([AllowedFilter::scope('search')])->paginate(10)->appends(request()->query());
            return response()->json([
                'success' => true,
                'adopts' => $adopts->items(),
                'page' => [
                    'size' => $adopts->perPage(),
                    'total_elements' => $adopts->total(),
                    'total_pages' => $adopts->lastPage(),
                    'number' => $adopts->currentPage(),
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
                'member_id' => ['required', 'exists:members,id'],
                'puppy_id' => ['required', 'exists:puppies,id'],
                'date' => ['required', 'date_format:Y-m-d'],
                'note' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Adopt::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'New adopt has been stored!',
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
    public function show(Adopt $adopt)
    {
        try {
            return response()->json([
                'success' => true,
                'adopt' => $adopt,
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
    public function edit(Adopt $adopt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adopt $adopt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adopt $adopt)
    {
        try {
            $adopt->delete();
            return response()->json([
                'success' => true,
                'message' => 'Adopt has been deleted!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }
}
