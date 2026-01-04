<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function reportingIndex(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $view = $request->get('view', 'monthly');

        // Capture filters (Used primarily for Overview)
        $faculty = $request->get('faculty');
        $college = $request->get('college');
        $vehicleType = $request->get('vehicleType');

        // 1. Logic for BOOKING OVERVIEW (With Faculty, College, Vehicle Filters)
        if ($view === 'overview') {
            $query = DB::table('booking')
                ->join('users', 'booking.userID', '=', 'users.userID')
                ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID');

            // Apply specific filters
            if ($faculty) $query->where('users.faculty', $faculty);
            if ($college) $query->where('users.college', $college);
            if ($vehicleType) $query->where('vehicles.vehicleType', $vehicleType);

            // Fetch Data
            $statusCounts = (clone $query)
                ->select('bookingStatus', DB::raw('count(*) as count'))
                ->whereYear('booking.created_at', $year)
                ->groupBy('bookingStatus')
                ->get();

            $rewardStats = (clone $query)
                ->select('rewardApplied', DB::raw('count(*) as count'))
                ->whereYear('booking.created_at', $year)
                ->groupBy('rewardApplied')
                ->get();

            $recentBookings = (clone $query)
                ->select('booking.*')
                ->orderBy('booking.created_at', 'desc')
                ->limit(5)
                ->get();

            return view('admin.reporting', compact(
                'statusCounts', 'rewardStats', 'recentBookings', 
                'year', 'month', 'view', 'faculty', 'college', 'vehicleType'
            ));
        }

        // 2. Base Query for INCOME (Monthly/Daily)
        $incomeQuery = DB::table('payment')
            ->where('paymentStatus', 'completed');

        if ($view === 'daily') {
            $results = (clone $incomeQuery)
                ->select(DB::raw('DAY(paymentDate) as day'), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as sales_count'))
                ->whereYear('paymentDate', $year)
                ->whereMonth('paymentDate', $month)
                ->groupBy('day')->orderBy('day')->get();

            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $chartLabels = range(1, $daysInMonth);
            $chartData = array_fill(0, $daysInMonth, 0);
            foreach ($results as $data) { $chartData[$data->day - 1] = (float) $data->total; }
            $reportStats = $results;

        } else {
            // Monthly logic
            $results = (clone $incomeQuery)
                ->select(DB::raw('MONTH(paymentDate) as month_num'), DB::raw('MONTHNAME(paymentDate) as month_name'), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as sales_count'))
                ->whereYear('paymentDate', $year)
                ->groupBy('month_num', 'month_name')->orderBy('month_num')->get();

            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chartData = array_fill(0, 12, 0);
            foreach ($results as $data) { $chartData[$data->month_num - 1] = (float) $data->total; }
            $reportStats = $results;
        }

        return view('admin.reporting', compact('chartData', 'chartLabels', 'reportStats', 'year', 'month', 'view'));
    }

    public function exportReport(Request $request)
{
    $year = $request->get('year', date('Y'));
    $month = $request->get('month', date('m'));
    $view = $request->get('view', 'monthly');

    // Fetch the same data used in your charts
    if ($view === 'daily') {
        $fileName = "Daily_Report_{$month}_{$year}.csv";
        $data = DB::table('payment')
            ->select(DB::raw('DAY(paymentDate) as label'), DB::raw('COUNT(*) as sales'), DB::raw('SUM(amount) as total'))
            ->whereYear('paymentDate', $year)
            ->whereMonth('paymentDate', $month)
            ->where('paymentStatus', 'completed')
            ->groupBy('label')->get();
        $header = ['Day', 'No of Sales', 'Total Amount (RM)'];
    } else {
        $fileName = "Monthly_Report_{$year}.csv";
        $data = DB::table('payment')
            ->select(DB::raw('MONTHNAME(paymentDate) as label'), DB::raw('COUNT(*) as sales'), DB::raw('SUM(amount) as total'))
            ->whereYear('paymentDate', $year)
            ->where('paymentStatus', 'completed')
            ->groupBy(DB::raw('MONTH(paymentDate)'), 'label')->orderBy(DB::raw('MONTH(paymentDate)'))->get();
        $header = ['Month', 'No of Sales', 'Total Amount (RM)'];
    }

    // Generate CSV
    $callback = function() use ($data, $header) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $header);
        foreach ($data as $row) {
            fputcsv($file, [$row->label, $row->sales, number_format($row->total, 2)]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ]);
}
}