<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dedoc\Scramble\Attributes\QueryParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.index')->only('index');
        $this->middleware('permission:users.show')->only('show');
        $this->middleware('permission:users.update')->only('update');
        $this->middleware('permission:users.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @response array{
     *   success: bool,
     *   code: int,
     *   message: string,
     *   data: User[],
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
    #[QueryParameter('filter[id]', type: 'integer')]
    #[QueryParameter('filter[name]', type: 'string')]
    #[QueryParameter('filter[phone]', type: 'string')]
    #[QueryParameter('filter[username]', type: 'string')]
    #[QueryParameter('filter[email]', type: 'string')]
    #[QueryParameter('filter[role]', type: 'string')]
    #[QueryParameter('include', type: 'string')]
    #[QueryParameter('paginate', type: 'integer')]
    #[QueryParameter('sort', type: 'string')]
    #[QueryParameter('page', type: 'integer')]
    public function index()
    {
        try {
            $paginate = request()->integer('paginate', 10);
            $users = QueryBuilder::for(User::class)->allowedFilters(['id', 'name', 'phone', 'username', 'email', AllowedFilter::scope('role')])->allowedIncludes('roles.permissions')->defaultSort('-created_at')->allowedSorts(['name', 'phone', 'email', 'created_at'])->paginate($paginate)->appends(request()->query());
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Users retrieved successfully!',
                'data' => $users->items(),
                'meta' => [
                    'paginate' => [
                        'size' => $users->perPage(),
                        'total_elements' => $users->total(),
                        'total_pages' => $users->lastPage(),
                        'number' => $users->currentPage(),
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
                'username' => ['required', 'alpha_dash:ascii', 'min:8', 'max:31', 'unique:users,username'],
                'email' => ['required', 'email:dns', 'min:8', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8', 'max:255'],
                'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
            ]);
            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);
            unset($validatedData['password_confirmation']);
            $user = User::create($validatedData);
            $user->assignRole('admin');
            return response()->json([
                'success' => true,
                'code' => 201,
                'message' => 'New user has been stored!',
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
    #[QueryParameter('include', type: 'string')]
    public function show(User $user)
    {
        try {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'User retrieved successfully!',
                'data' => QueryBuilder::for(User::class)->allowedIncludes('roles.permissions')->where('id', '=', $user->id)->firstOrFail(),
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
                    'code' => 422,
                    'message' => 'Validation failed! Please check the input fields.',
                    'errors' => $validator->errors()
                ], 422);
            $validatedData = $validator->validated();
            if (isset($validatedData['password']))
                $validatedData['password'] = Hash::make($validatedData['password']);
            unset($validatedData['password_confirmation']);
            $user->update($validatedData);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'User has been updated!',
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
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'User has been deleted!',
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
