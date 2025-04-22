<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Diner;
use App\Models\Reservation;
use App\Models\Table;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reservations = Reservation::orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('reservas/index', [
            'reservations' => ReservationResource::collection($reservations),
        ]);
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
        try {
            //Validacion de la tabla
            $validated = $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|date_format:H:i',
                'number_of_people' => 'required|integer|min:1|max:20',
                'diner_id' => 'required|exists:diners,id',
                'table_id' => 'required|exists:tables,id',
            ]);

            // dd($validated);
            Reservation::create($validated);

            Table::where('id', $validated['table_id'])
                ->update(['status' => 'reserved']);

            return redirect()->route('reservations.index')->with('success', 'Diner created successfully');
        } catch (Exception $e) {
            // dd($e);
            return redirect()->route('reservations.index')->with('error', 'Error creating table');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
        try {
            // Return only the specific diner data for partial reload
            return response()->json([
                'success' => true,
                'diner' => $reservation,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el comensal',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'telephone' => 'required|digits:9',
            'address' => 'required|string|min:5',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Diner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
        $reservation->delete();

        $reservation->table()->update(['status' => 'available']);

        return redirect()->route('reservations.index')->with('success', 'Diner deleted successfully');
    }

    /**
     * Get all available tables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * This method retrieves all tables with the status "available" from the database.
     * The tables are ordered by their creation date in descending order and only the
     * `id` and `number` fields are selected. The data is returned as a JSON response.
     *
     * Example Response:
     * {
     *     "tables": [
     *         { "id": 1, "number": "M01" },
     *         { "id": 2, "number": "M02" }
     *     ]
     * }
     */
    public function tablesAvailables(Request $request)
    {
        //Traer todas las mesas disponibles
        $tables = Table::where('status', '=', "available")
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'number', 'capacity']);
        return response()->json(['tables' => $tables]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $diners = Diner::where('name', 'like', "%{$query}%")
            ->whereDoesntHave('reservations')
            ->take(10)
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'name']);

        return response()->json(['diners' => $diners]);
    }

    public function activeReservations()
    {
        $reservations = Reservation::where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'count' => $reservations,
        ]);
    }


}
