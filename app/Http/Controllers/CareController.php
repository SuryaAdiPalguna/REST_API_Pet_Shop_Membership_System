<?php

namespace App\Http\Controllers;

use App\Models\Care;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class CareController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cares.index')->only('index');
        $this->middleware('permission:cares.store')->only('store');
        $this->middleware('permission:cares.show')->only('show');
        $this->middleware('permission:cares.update')->only('update');
        $this->middleware('permission:cares.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: Care[],
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
            $cares = QueryBuilder::for(Care::class)->allowedFilters(['id', 'name', AllowedFilter::scope('search')])->defaultSort('-created_at')->allowedSorts(['name', 'created_at'])->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Cares retrieved successfully!',
                'data' => $cares->items(),
                'meta' => [
                    'paginate' => [
                        'size' => $cares->perPage(),
                        'total_elements' => $cares->total(),
                        'total_pages' => $cares->lastPage(),
                        'number' => $cares->currentPage(),
                    ],
                ],
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
                'description' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors(),
                ], 422);
            $validatedData = $validator->validated();
            Care::create($validatedData);
            return response()->json([
                'success' => true,
                'code' => 201,
                'message' => 'New care has been stored!',
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
    public function show(Care $care)
    {
        try {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Care retrieved successfully!',
                'data' => $care,
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
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors(),
                ], 422);
            $validatedData = $validator->validated();
            $care->update($validatedData);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Care has been updated!',
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
    public function destroy(Care $care)
    {
        try {
            $care->delete();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Care has been deleted!',
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
