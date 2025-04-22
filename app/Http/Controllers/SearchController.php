<?php

namespace App\Http\Controllers;

use App\Models\Diner;
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
}
