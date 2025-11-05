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

        // Extract the column name dynamically to fix the undefined property issue
        if (empty($tables)) {
            $tableNames = [];
        } else {
            // Get the first stdClass property name
            $firstTableObj = (array)$tables[0];
            $firstKey = array_key_first($firstTableObj);
            $tableNames = array_map(fn($table) => $table->$firstKey, $tables);
        }

        return view('superadmin.database.index', compact('tableNames'));
    }

    /**
     * Show data from a selected table.
     */
    public function show($table)
    {
        // Get all valid table names
        $tablesRaw = DB::select('SHOW TABLES');
        if (empty($tablesRaw)) {
            abort(404, 'Table not found.');
        }
        $firstTableObj = (array)$tablesRaw[0];
        $firstKey = array_key_first($firstTableObj);

        $tables = collect($tablesRaw)
            ->map(fn($t) => $t->$firstKey)
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
