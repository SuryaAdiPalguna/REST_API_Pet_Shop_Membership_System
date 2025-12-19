<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Puppy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $total_puppies = Puppy::count();
            $total_puppies_last_month = Puppy::where('created_at', '<=', now()->subMonth()->endOfMonth())->count();
            $total_puppies_growth_percentage = 0;
            if ($total_puppies_last_month > 0)
                $total_puppies_growth_percentage = round(($total_puppies - $total_puppies_last_month) / $total_puppies_last_month * 100, 1);
            $available_puppies = Puppy::whereDoesntHave('adopts')->count();
            $available_puppies_last_month = Puppy::whereDoesntHave('adopts')->where('created_at', '<=', now()->subMonth()->endOfMonth())->count();
            $available_puppies_growth_percentage = 0;
            if ($available_puppies_last_month > 0)
                $available_puppies_growth_percentage = round(($available_puppies - $available_puppies_last_month) / $available_puppies_last_month * 100, 1);
            $active_members = Member::where('is_active', '=', true)->count();
            $active_members_last_month = Member::where('is_active', '=', true)->where('created_at', '<=', now()->subMonth()->endOfMonth())->count();
            $active_members_growth_percentage = 0;
            if ($active_members_last_month > 0)
                $active_members_growth_percentage = round(($active_members - $active_members_last_month) / $active_members_last_month * 100, 1);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Dashboard retrieved successfully!',
                'data' => [
                    'total_puppies' => [
                        'value' => $total_puppies,
                        'growth_percentage' => $total_puppies_growth_percentage,
                        'period' => 'last_month',
                    ],
                    'available_puppies' => [
                        'value' => $available_puppies,
                        'growth_percentage' => $available_puppies_growth_percentage,
                        'period' => 'last_month',
                    ],
                    'active_members' => [
                        'value' => $active_members,
                        'growth_percentage' => $active_members_growth_percentage,
                        'period' => 'last_month',
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
    public function show(Puppy $puppy)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puppy $puppy)
    {
        //
    }
}
