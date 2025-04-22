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
     *
     * @return \Inertia\Response
     *
     * This method retrieves all diners from the database, orders them by their creation date in descending order,
     * and returns them as a collection of `DinerResource` to the `comensales/index` view.
     */
    public function index()
    {
        $diners = Diner::orderBy('created_at', 'desc')->get();

        return Inertia::render('comensales/index', [
            'diners' => DinerResource::collection($diners),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     *
     * This method is currently not implemented. It is intended to display a form for creating a new diner.
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
     * This method validates the input data and creates a new diner in the database.
     * If successful, it redirects to the `diners.index` route with a success message.
     * If an error occurs, it throws an exception.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email',
                'telephone' => 'required|digits:9',
                'address' => 'required|string|min:5',
            ]);

            Diner::create($validated);

            return redirect()->route('diners.index')->with('success', 'Diner created successfully');
        } catch (Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Diner $diner
     * @return void
     *
     * This method is currently not implemented. It is intended to display the details of a specific diner.
     */
    public function show(Diner $diner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Diner $diner
     * @return \Illuminate\Http\JsonResponse
     *
     * This method retrieves the data of a specific diner and returns it as a JSON response.
     * If an error occurs, it returns a JSON response with an error message.
     */
    public function edit(Diner $diner)
    {
        try {
            return response()->json([
                'success' => true,
                'diner' => $diner,
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
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Diner $diner
     * @return \Illuminate\Http\RedirectResponse
     *
     * This method validates the input data and updates the specified diner in the database.
     * If successful, it redirects to the `diners.index` route with a success message.
     */
    public function update(Request $request, Diner $diner)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'telephone' => 'required|digits:9',
            'address' => 'required|string|min:5',
        ]);

        $diner->update($validated);

        return redirect()->route('diners.index')->with('success', 'Diner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Diner $diner
     * @return \Illuminate\Http\RedirectResponse
     *
     * This method deletes the specified diner from the database.
     * If successful, it redirects to the `diners.index` route with a success message.
     */
    public function destroy(Diner $diner)
    {
        $diner->delete();

        return redirect()->route('diners.index')->with('success', 'Diner deleted successfully');
    }
}
