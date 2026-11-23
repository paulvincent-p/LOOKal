<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('business')->orderByRaw("status = 'open' desc")->latest()->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    public function resolve(Report $report)
    {
        $report->update(['status' => 'resolved']);

        return back()->with('status', 'Report marked as resolved.');
    }
}
