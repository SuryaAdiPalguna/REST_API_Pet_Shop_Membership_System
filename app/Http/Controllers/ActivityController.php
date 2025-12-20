<?php

namespace App\Http\Controllers;

use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:activities.index')->only('index');
    }

    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: Activity[],
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
    #[QueryParameter('paginate', type: 'integer')]
    #[QueryParameter('sort', type: 'string')]
    #[QueryParameter('page', type: 'integer')]
    public function index()
    {
        try {
            $paginate = request()->integer('paginate', 10);
            $members = QueryBuilder::for(Activity::class)->allowedFilters(['id'])->defaultSort('-created_at')->allowedSorts('created_at')->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Activities retrieved successfully!',
                'data' => $members->items(),
                'meta' => [
                    'paginate' => [
                        'size' => $members->perPage(),
                        'total_elements' => $members->total(),
                        'total_pages' => $members->lastPage(),
                        'number' => $members->currentPage(),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
