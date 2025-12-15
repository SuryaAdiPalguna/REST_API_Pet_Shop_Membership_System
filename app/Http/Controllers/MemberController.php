<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $members = QueryBuilder::for(Member::class)->allowedFilters([AllowedFilter::scope('search')])->paginate(10)->appends(request()->query());
            return response()->json([
                'success' => true,
                'members' => $members->items(),
                'page' => [
                    'size' => $members->perPage(),
                    'total_elements' => $members->total(),
                    'total_pages' => $members->lastPage(),
                    'number' => $members->currentPage(),
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
                'phone' => ['required', 'string', 'min:8', 'max:15'],
                'email' => ['required', 'email:dns', 'min:8', 'max:255', 'unique:members,email'],
                'address' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            Member::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'New member has been stored!',
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
    public function show(Member $member)
    {
        try {
            return response()->json([
                'success' => true,
                'member' => $member,
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
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $member->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Member has been updated!',
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
    public function destroy(Member $member)
    {
        try {
            $member->delete();
            return response()->json([
                'success' => true,
                'message' => 'Member has been deleted!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }
}
