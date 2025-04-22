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
     *
     * @return \Inertia\Response
     *
     * This method retrieves all tables from the database, orders them by their creation date in descending order,
     * and returns them as a collection of `TableResource` to the `mesas/index` view.
     */
    public function index()
    {
        $tables = Table::orderBy('created_at', 'desc')->get();
        return Inertia::render('mesas/index', [
            'tables' => TableResource::collection($tables),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     *
     * This method is currently not implemented. It is intended to display a form for creating a new table.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * This method validates the input data, generates a new table number, and creates a new table in the database.
     * If successful, it redirects to the `tables.index` route with a success message.
     * If an error occurs, it redirects with an error message.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'capacity' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:20',
                ],
            ]);

            $lastTable = Table::orderBy('id', 'desc')->first();
            $lastNumber = $lastTable ? intval(substr($lastTable->number, 1)) : 0;

            $table = new Table();
            $table->number = sprintf("M%02d", $lastNumber + 1);
            $table->capacity = $validated['capacity'];
            $table->status = "available";
            $table->save();

            return redirect()->route('tables.index')->with('success', 'Tables created successfully');
        } catch (Exception $e) {
            return redirect()->route('tables.index')->with('error', 'Error creating table');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Table $table
     * @return void
     *
     * This method is currently not implemented. It is intended to display the details of a specific table.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Table $table
     * @return \Illuminate\Http\JsonResponse
     *
     * This method retrieves the data of a specific table and returns it as a JSON response.
     * If an error occurs, it returns a JSON response with an error message.
     */
    public function edit(Table $table)
    {
        try {
            return response()->json([
                'success' => true,
                'table' => $table,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la mesa',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Table $table
     * @return \Illuminate\Http\RedirectResponse
     *
     * This method validates the input data and updates the specified table in the database.
     * If successful, it redirects to the `tables.index` route with a success message.
     */
    public function update(Request $request, Table $table)
    {
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
     *
     * @param \App\Models\Table $table
     * @return \Illuminate\Http\RedirectResponse
     *
     * This method deletes the specified table from the database.
     * If successful, it redirects to the `tables.index` route with a success message.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Table deleted successfully');
    }

    /**
     * Get the count of available tables.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * This method retrieves the count of tables with the status "available" and returns it as a JSON response.
     */
    public function available()
    {
        dd('available');
        $tables = Table::where('status', 'available')->count();

        return response()->json([
            'success' => true,
            'count' => $tables,
        ]);
    }

}
