<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                  ->orWhere('module', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->module) {
            $query->where('module', $request->module);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->get();

        $modules = ActivityLog::select('module')->distinct()->pluck('module');

        return view('activity-logs.index', compact('logs', 'modules'));
    }
}