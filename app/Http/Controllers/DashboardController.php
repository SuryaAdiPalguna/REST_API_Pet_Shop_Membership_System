<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Puppy;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Throwable;

use function Symfony\Component\Clock\now;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[QueryParameter('start_date', type: 'string')]
    #[QueryParameter('end_date', type: 'string')]
    public function index()
    {
        try {
            request()->validate([
                'start_date' => ['nullable', 'date_format:Y-m-d'],
                'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            ]);
            $start_date = request()->query('start_date') ? Carbon::parse(request()->query('start_date'))->endOfDay() : Carbon::now()->subMonth()->endOfMonth();
            $end_date = request()->query('end_date') ? Carbon::parse(request()->query('end_date'))->endOfDay() : Carbon::now()->endOfMonth();
            $total_puppies = Puppy::where('created_at', '<=', $end_date)->count();
            $total_puppies_last_time = Puppy::where('created_at', '<=', $start_date)->count();
            $total_puppies_growth_percentage = $total_puppies_last_time > 0 ? round(($total_puppies - $total_puppies_last_time) / $total_puppies_last_time * 100, 1) : 0;
            $available_puppies = Puppy::whereDoesntHave('adopts')->where('created_at', '<=', $end_date)->count();
            $available_puppies_last_time = Puppy::whereDoesntHave('adopts')->where('created_at', '<=', $start_date)->count();
            $available_puppies_growth_percentage = $available_puppies_last_time > 0 ? round(($available_puppies - $available_puppies_last_time) / $available_puppies_last_time * 100, 1) : 0;
            $active_members = Member::where('is_active', '=', true)->where('created_at', '<=', $end_date)->count();
            $active_members_last_time = Member::where('is_active', '=', true)->where('created_at', '<=', $start_date)->count();
            $active_members_growth_percentage = $active_members_last_time > 0 ? round(($active_members - $active_members_last_time) / $active_members_last_time * 100, 1) : 0;
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Dashboard retrieved successfully!',
                'data' => [
                    'total_puppies' => [
                        'value' => $total_puppies,
                        'growth_percentage' => $total_puppies_growth_percentage,
                        'period' => [
                            'start_date' => $start_date->toDateString(),
                            'end_date' => $end_date->toDateString(),
                        ],
                    ],
                    'available_puppies' => [
                        'value' => $available_puppies,
                        'growth_percentage' => $available_puppies_growth_percentage,
                        'period' => [
                            'start_date' => $start_date->toDateString(),
                            'end_date' => $end_date->toDateString(),
                        ],
                    ],
                    'active_members' => [
                        'value' => $active_members,
                        'growth_percentage' => $active_members_growth_percentage,
                        'period' => [
                            'start_date' => $start_date->toDateString(),
                            'end_date' => $end_date->toDateString(),
                        ],
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
