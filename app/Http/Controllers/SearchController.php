<?php

namespace App\Http\Controllers;

use App\Models\Diner;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //

    public function available()
    {
        $tables = Table::where('status', 'available')->count();

        return response()->json([
            'success' => true,
            'count' => $tables,
        ]);
    }

    /**
     * Get the quantity of reservations per day for the last 7 days.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * This method retrieves the count of reservations grouped by day for the last 7 days.
     * The data is returned as a JSON response with the date and count for each day.
     */
    public function reservationsLast7Days()
    {
        $reservations = Reservation::selectRaw('date as date, COUNT(*) as count')
            ->whereBetween('date', [now()->subDays(7)->toDateString(), now()->toDateString()]) // Filter for the next 7 days
            ->groupBy('date') // Group by the `date` field
            ->orderBy('date', 'asc') // Order by date in ascending order
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }

    /**
     * Get the quantity of reservations per day for the next 7 days using the `date` field.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * This method retrieves the count of reservations grouped by the `date` field for the next 7 days.
     * The data is returned as a JSON response with the date and count for each day.
     */
    public function reservationsNext7Days()
    {
        $reservations = Reservation::selectRaw('date as date, COUNT(*) as count')
            ->whereBetween('date', [now()->toDateString(), now()->addDays(7)->toDateString()]) // Filter for the next 7 days
            ->groupBy('date') // Group by the `date` field
            ->orderBy('date', 'asc') // Order by date in ascending order
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }
}
