<?php

namespace App\Http\Controllers;

use App\Http\Resources\TableResource;
use App\Models\Table;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener Mesas y ordenarlos por created_at DESC usando laravel Resources
        $tables = Table::orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('mesas/index', [
            'tables' => TableResource::collection($tables),
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
                'capacity' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:20',
                ],
            ]);
            // dd($validated);
            $lastTable = Table::orderBy('id', 'desc')->first();
            $lastNumber = $lastTable ? intval(substr($lastTable->number, 1)) : 0;

            // Create a new Table instance with custom attributes
            $table = new Table();
            $table->number = sprintf("M%02d", $lastNumber + 1);
            $table->capacity = $validated['capacity'];
            $table->status = "available";
            $table->save();


            // dd($inserted);
            return redirect()->route('tables.index')->with('success', 'Tables created successfully');
        } catch (Exception $e) {
            // dd($e);
            return redirect()->route('tables.index')->with('error', 'Error creating table');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        //
        try {
            // Return only the specific diner data for partial reload
            return response()->json([
                'success' => true,
                'table' => $table,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la mersa',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        //
        $validated = $request->validate([
            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
        ]);

        $table->update($validated);

        return redirect()->route('tables.index')->with('success', 'Table updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        //
        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Table deleted successfully');
    }
}
