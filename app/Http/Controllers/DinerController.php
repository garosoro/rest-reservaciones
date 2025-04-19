<?php

namespace App\Http\Controllers;

use App\Http\Resources\DinerResource;
use App\Models\Diner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DinerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // dd(DinerResource::collection(Diner::all()));
        return Inertia::render('comensales/index', [
            'diners' => DinerResource::collection(Diner::all()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('diners/create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Diner::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('diners.index');
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
    public function edit(Diner $diner)
    {
        //
        $post = Diner::findOrFail($diner);
        return Inertia::render('diners/edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diner $diner)
    {
        //
        $post = Diner::findOrFail($diner);
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('diners.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diner $diner)
    {
        //
        $post = Diner::findOrFail($diner);
        $post->delete();

        return redirect()->route('diners.index');
    }
}
