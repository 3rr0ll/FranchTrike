<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DatabaseController extends Controller
{
    /**
     * Show all database tables and allow superadmin to view or export backup.
     */
    public function index()
    {
        // Get all table names
        $tables = DB::select('SHOW TABLES');

        // Extract key (depends on database name)
        $key = 'Tables_in_' . env('DB_DATABASE');
        $tableNames = array_map(fn($table) => $table->$key, $tables);

        return view('superadmin.database.index', compact('tableNames'));
    }

    /**
     * Show data from a selected table.
     */
    public function show($table)
    {
        // Get all valid table names
        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn($t) => array_values((array)$t)[0])
            ->toArray();
    
        // Check if given table exists in list
        if (!in_array($table, $tables)) {
            abort(404, 'Table not found.');
        }
    
        // Fetch limited data
        $data = DB::table($table)->limit(50)->get();
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
    
        return view('superadmin.database.show', compact('table', 'columns', 'data'));
    }

}
