<?php

namespace App\Http\Controllers;

use App\Models\Diner;
use App\Models\Table;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
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
    public function tablesAvailables(Request $request)
    {
        //Traer todas las mesas disponibles
        $tables = Table::where('status', '=', "available")
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'number']);
        return response()->json(['tables' => $tables]);
    }
}
