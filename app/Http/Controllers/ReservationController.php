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
}
