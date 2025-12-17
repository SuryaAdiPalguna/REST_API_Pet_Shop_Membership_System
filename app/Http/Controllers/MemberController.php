<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: Member[],
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
    #[QueryParameter('filter[phone]', type: 'string')]
    #[QueryParameter('filter[email]', type: 'string')]
    #[QueryParameter('filter[search]', type: 'string')]
    #[QueryParameter('paginate', type: 'integer')]
    #[QueryParameter('sort', type: 'string')]
    #[QueryParameter('page', type: 'integer')]
    public function index()
    {
        try {
            $paginate = request()->integer('paginate', 10);
            $members = QueryBuilder::for(Member::class)->allowedFilters(['id', 'name', 'phone', 'email', AllowedFilter::scope('search')])->defaultSort('-created_at')->allowedSorts(['name', 'phone', 'email', 'created_at'])->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Members retrieved successfully!',
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
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:4', 'max:255'],
                'phone' => ['required', 'string', 'min:8', 'max:15'],
                'email' => ['required', 'email:dns', 'min:8', 'max:255', 'unique:members,email'],
                'address' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Member::create($validatedData);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'New member has been stored!',
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
    public function show(Member $member)
    {
        try {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Member retrieved successfully!',
                'data' => $member,
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
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['sometimes', 'string', 'min:4', 'max:255'],
                'phone' => ['sometimes', 'string', 'min:8', 'max:15'],
                'email' => ['sometimes', 'email:dns', 'min:8', 'max:255', "unique:members,email,{$member->id},id"],
                'address' => ['sometimes', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $member->update($validatedData);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Member has been updated!',
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
    public function destroy(Member $member)
    {
        try {
            $member->delete();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Member has been deleted!',
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
