<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Business;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'pending' => Business::where('status', 'pending')->count(),
            'approved' => Business::where('status', 'approved')->count(),
            'rejected' => Business::where('status', 'rejected')->count(),
            'owners' => User::where('role', 'owner')->count(),
            'reviews' => Review::count(),
            'reports' => Report::where('status', 'open')->count(),
        ];

        $actions = ActivityLog::selectRaw('action, COUNT(*) as total')->groupBy('action')->pluck('total', 'action');

        $top = ActivityLog::selectRaw('business_id, COUNT(*) as total')
            ->where('action', 'view')
            ->groupBy('business_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $names = Business::whereIn('id', $top->pluck('business_id'))->pluck('name', 'id');

        return view('admin.dashboard', compact('counts', 'actions', 'top', 'names'));
    }
}
