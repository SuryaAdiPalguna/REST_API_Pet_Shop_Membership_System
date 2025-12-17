<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: Breed[],
     *   meta: array{
     *     paginate: array{
     *       size: int,
     *       total_elements: int,
     *       total_pages: int,
     *       number: int
     *     }
     *   }
     * }
     */
    #[QueryParameter('filter[id]', type: 'string')]
    #[QueryParameter('filter[name]', type: 'string')]
    #[QueryParameter('filter[search]', type: 'string')]
    #[QueryParameter('paginate', type: 'integer')]
    #[QueryParameter('sort', type: 'string')]
    #[QueryParameter('page', type: 'integer')]
    public function index()
    {
        try {
            $paginate = request()->integer('paginate', 10);
            $breeds = QueryBuilder::for(Breed::class)->allowedFilters(['id', 'name', AllowedFilter::scope('search')])->defaultSort('-created_at')->allowedSorts(['name', 'created_at'])->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Breeds retrieved successfully!',
                'data' => $breeds->items(),
                'meta' => [
                    'paginate' => [
                        'size' => $breeds->perPage(),
                        'total_elements' => $breeds->total(),
                        'total_pages' => $breeds->lastPage(),
                        'number' => $breeds->currentPage(),
                    ],
                ]
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'An unexpected error occurred! Please try again later.',
                'error' => config('app.debug') ? $error->getMessage() : null,
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
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Breed::create($validatedData);
            return response()->json([
                'success' => true,
                'code' => 201,
                'message' => 'New breed has been stored!',
            ], 201);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'An unexpected error occurred! Please try again later.',
                'error' => config('app.debug') ? $error->getMessage() : null,
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
                'code' => 200,
                'message' => 'Breed retrieved successfully!',
                'data' => $breed,
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'An unexpected error occurred! Please try again later.',
                'error' => config('app.debug') ? $error->getMessage() : null,
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
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $breed->update($validatedData);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Breed has been updated!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'An unexpected error occurred! Please try again later.',
                'error' => config('app.debug') ? $error->getMessage() : null,
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
                'code' => 200,
                'message' => 'Breed has been deleted!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'An unexpected error occurred! Please try again later.',
                'error' => config('app.debug') ? $error->getMessage() : null,
            ], 500);
        }
    }
}
