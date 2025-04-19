<?php

namespace App\Http\Controllers;

use App\Http\Resources\DinerResource;
use App\Models\Diner;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DinerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener Diners y ordenarlos por created_at DESC
        $diners = Diner::select('id', 'name', 'email')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('comensales/index', [
            'diners' => DinerResource::collection($diners),
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
            //Validacion de comensal
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email',
                'telephone' => 'required|digits:9',
                'address' => 'required|string|min:5',
            ]);

            Diner::create($validated);
            // dd($inserted);
            return redirect()->route('comensales.index')->with('success', 'Diner created successfully');
        } catch (Exception $e) {
            dd($e);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Diner $diner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diner $comensale)
    {
        try {
            // Return only the specific diner data for partial reload
            return response()->json([
                'success' => true,
                'diner' => $comensale,
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
    public function update(Request $request, Diner $comensale)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'telephone' => 'required|digits:9',
            'address' => 'required|string|min:5',
        ]);

        $comensale->update($validated);

        return redirect()->route('comensales.index')->with('success', 'Diner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diner $comensale)
    {
        //
        $comensale->delete();

        return redirect()->route('comensales.index')->with('success', 'Diner deleted successfully');
    }
}
