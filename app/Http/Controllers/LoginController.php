<?php

namespace App\Http\Controllers;

use App\Data\Login\LoginData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $credentials = $validator->validated();
            $user = User::where('email', '=', $credentials['email'])->first();
            if (!$user || !Hash::check($credentials['password'], $user->password))
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Login failed! Please try again.',
                ], 401);
            $token = $user->createToken('api')->plainTextToken;
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Login successful!',
                'data' => $user,
                'meta' => [
                    'token' => $token,
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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
