<?php


namespace App\Http\Controllers;
use App\Models\AlarmHistory;


use Illuminate\Http\Request;


class DatabaseController extends Controller
{
    // Clear the AlarmHistory table
    public function clearDatabase()
    {
        AlarmHistory::truncate();
        return redirect()->back()->with('success', 'Database cleared successfully.');
    }
}

