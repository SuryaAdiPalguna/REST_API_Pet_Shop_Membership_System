<?php

namespace App\Http\Controllers;

use App\Models\Adopt;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class AdoptController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:adopts.index')->only('index');
        $this->middleware('permission:adopts.store')->only('store');
        $this->middleware('permission:adopts.show')->only('show');
        $this->middleware('permission:adopts.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: Adopt[],
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
    #[QueryParameter('filter[member_id]', type: 'string')]
    #[QueryParameter('filter[puppy_id]', type: 'string')]
    #[QueryParameter('filter[search]', type: 'string')]
    #[QueryParameter('include', type: 'string')]
    #[QueryParameter('paginate', type: 'integer')]
    #[QueryParameter('sort', type: 'string')]
    #[QueryParameter('page', type: 'integer')]
    public function index()
    {
        try {
            $paginate = request()->integer('paginate', 10);
            $adopts = QueryBuilder::for(Adopt::class)->allowedFilters(['id', 'member_id', 'puppy_id', AllowedFilter::scope('search')])->allowedIncludes('puppy.puppy_cares')->defaultSort('-created_at')->allowedSorts('created_at')->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Adopts retrieved successfully!',
                'data' => $adopts->items(),
                'meta' => [
                    'paginate' => [
                        'size' => $adopts->perPage(),
                        'total_elements' => $adopts->total(),
                        'total_pages' => $adopts->lastPage(),
                        'number' => $adopts->currentPage(),
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
                'member_id' => ['required', 'exists:members,id'],
                'puppy_id' => ['required', 'exists:puppies,id'],
                'date' => ['required', 'date_format:Y-m-d'],
                'note' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Adopt::create($validatedData);
            return response()->json([
                'success' => true,
                'code' => 201,
                'message' => 'New adopt has been stored!',
                'data' => $validatedData,
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
    public function show(Adopt $adopt)
    {
        try {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Adopt retrieved successfully!',
                'data' => QueryBuilder::for(Adopt::class)->allowedIncludes('puppy.puppy_cares')->where('id', '=', $adopt->id)->firstOrFail(),
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
                'code' => 200,
                'message' => 'Adopt has been deleted!',
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
