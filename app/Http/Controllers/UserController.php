<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = QueryBuilder::for(User::class)->allowedFilters([])->paginate(10)->appends(request()->query());
            return response()->json([
                'success' => true,
                'users' => $users->items(),
                'page' => [
                    'size' => $users->perPage(),
                    'total_elements' => $users->total(),
                    'total_pages' => $users->lastPage(),
                    'number' => $users->currentPage(),
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
                'username' => ['required', 'alpha_dash:ascii', 'min:8', 'max:31', 'unique:users,username'],
                'email' => ['required', 'email:dns', 'min:8', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8', 'max:255'],
                'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);
            unset($validatedData['password_confirmation']);
            User::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'New user has been stored!',
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
    public function show(User $user)
    {
        try {
            return response()->json([
                'success' => true,
                'user' => $user,
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
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['sometimes', 'string', 'min:4', 'max:255'],
                'image' => ['sometimes', 'image', 'max:255'],
                'phone' => ['sometimes', 'string', 'min:8', 'max:15'],
                'username' => ['sometimes', 'alpha_dash:ascii', 'min:8', 'max:31', "unique:users,username,{$user->id},id"],
                'email' => ['sometimes', 'email:dns', 'min:8', 'max:255', "unique:users,email,{$user->id},id"],
                'password' => ['sometimes', 'confirmed', 'min:8', 'max:255'],
                'password_confirmation' => ['sometimes', 'string', 'min:8', 'max:255'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            if (isset($validatedData['password']))
                $validatedData['password'] = Hash::make($validatedData['password']);
            unset($validatedData['password_confirmation']);
            $user->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'User has been updated!',
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
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User has been deleted!',
            ]);
        } catch (Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
    }
}
