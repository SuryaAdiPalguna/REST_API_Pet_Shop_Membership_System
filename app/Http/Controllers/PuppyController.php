<?php

namespace App\Http\Controllers;

use App\Models\Puppy;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class PuppyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: Puppy[],
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
    #[QueryParameter('filter[breed_id]', type: 'string')]
    #[QueryParameter('filter[name]', type: 'string')]
    #[QueryParameter('filter[search]', type: 'string')]
    #[QueryParameter('include', type: 'string')]
    #[QueryParameter('paginate', type: 'integer')]
    #[QueryParameter('sort', type: 'string')]
    #[QueryParameter('page', type: 'integer')]
    public function index()
    {
        try {
            $paginate = request()->integer('paginate', 10);
            $puppies = QueryBuilder::for(Puppy::class)->allowedFilters(['id', 'breed_id', 'name', AllowedFilter::scope('search')])->allowedIncludes('puppy_cares')->defaultSort('-created_at')->allowedSorts(['name', 'created_at'])->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Puppies retrieved successfully!',
                'data' => $puppies->items(),
                'meta' => [
                    'paginate' => [
                        'size' => $puppies->perPage(),
                        'total_elements' => $puppies->total(),
                        'total_pages' => $puppies->lastPage(),
                        'number' => $puppies->currentPage(),
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
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'breed_id' => ['required', 'exists:breeds,id'],
                'name' => ['required', 'string', 'min:4', 'max:255'],
                'puppy_cares' => ['sometimes', 'array', 'min:1'],
                'puppy_cares.*.care_id' => ['required', 'exists:cares,id'],
                'puppy_cares.*.period' => ['required', 'integer', 'min:1'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $puppy = Puppy::create(collect($validatedData)->only(['breed_id', 'name'])->toArray());
            foreach ($validatedData['puppy_cares'] as $care)
                $puppy->puppy_cares()->create([
                    'care_id' => $care['care_id'],
                    'period' => $care['period'],
                ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'code' => 201,
                'message' => 'New puppy has been stored!',
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
    public function show(Puppy $puppy)
    {
        try {
            $puppy->load(['puppy_cares']);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Member retrieved successfully!',
                'data' => QueryBuilder::for(Puppy::class)->allowedIncludes('puppy_cares')->where('id', '=', $puppy->id)->firstOrFail(),
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
    public function edit(Puppy $puppy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puppy $puppy)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'breed_id' => ['sometimes', 'exists:breeds,id'],
                'name' => ['sometimes', 'string', 'min:4', 'max:255'],
                'puppy_cares' => ['sometimes', 'array', 'min:1'],
                'puppy_cares.*.care_id' => ['required_with:puppy_cares', 'exists:cares,id'],
                'puppy_cares.*.period' => ['required_with:puppy_cares', 'integer', 'min:1'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $puppy->update(collect($validatedData)->only(['breed_id', 'name'])->toArray());
            if (isset($validatedData['puppy_cares'])) {
                $puppy->puppy_cares()->delete();
                foreach ($validatedData['puppy_cares'] as $care)
                    $puppy->puppy_cares()->create([
                        'care_id' => $care['care_id'],
                        'period' => $care['period'],
                    ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Puppy has been updated!',
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
    public function destroy(Puppy $puppy)
    {
        try {
            $puppy->delete();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Puppy has been deleted!',
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
