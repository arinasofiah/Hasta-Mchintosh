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

    // Initialize variables that will be used in the view
    $chartData = [];
    $chartLabels = [];
    $reportStats = [];

    // 1. Logic for BOOKING OVERVIEW (With Faculty, College, Vehicle Filters)
    if ($view === 'overview') {
        // Base query for general stats
        $baseQuery = DB::table('booking')
            ->join('users', 'booking.userID', '=', 'users.userID')
            ->join('customer', 'users.userID', '=', 'customer.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->whereYear('booking.created_at', $year);

        // Apply filters to base query
        if ($faculty) $baseQuery->where('customer.faculty', $faculty);
        if ($college) $baseQuery->where('customer.college', $college);
        if ($vehicleType) $baseQuery->where('vehicles.vehicleType', $vehicleType);

        // Fetch general stats
        $statusCounts = (clone $baseQuery)
            ->select('bookingStatus', DB::raw('count(*) as count'))
            ->groupBy('bookingStatus')
            ->get();

        $rewardStats = (clone $baseQuery)
            ->select('rewardApplied', DB::raw('count(*) as count'))
            ->groupBy('rewardApplied')
            ->get();

        // Faculty distribution - respect faculty filter if set
        $facultyQuery = DB::table('booking')
            ->join('users', 'booking.userID', '=', 'users.userID')
            ->join('customer', 'users.userID', '=', 'customer.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->whereYear('booking.created_at', $year);

        if ($faculty) $facultyQuery->where('customer.faculty', $faculty);
        if ($vehicleType) $facultyQuery->where('vehicles.vehicleType', $vehicleType);
        // Note: Don't apply college filter to faculty distribution

        $facultyDistribution = $facultyQuery
            ->select('customer.faculty', DB::raw('count(*) as count'))
            ->groupBy('customer.faculty')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'faculty')
            ->toArray();

        // College distribution - respect college filter if set
        $collegeQuery = DB::table('booking')
            ->join('users', 'booking.userID', '=', 'users.userID')
            ->join('customer', 'users.userID', '=', 'customer.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->whereYear('booking.created_at', $year);

        if ($college) $collegeQuery->where('customer.college', $college);
        if ($vehicleType) $collegeQuery->where('vehicles.vehicleType', $vehicleType);
        // Note: Don't apply faculty filter to college distribution

        $collegeDistribution = $collegeQuery
            ->select('customer.college', DB::raw('count(*) as count'))
            ->groupBy('customer.college')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'college')
            ->toArray();

        // Get filtered count for stats box (when specific filter is selected)
        $filteredFacultyCount = $faculty ? ($facultyDistribution[$faculty] ?? 0) : 0;
        $filteredCollegeCount = $college ? ($collegeDistribution[$college] ?? 0) : 0;

        // Recent bookings with vehicle details - SIMPLIFIED VERSION
        $recentBookingsQuery = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select(
                'booking.bookingID',
                'booking.booking_code',
                'booking.bookingStatus',
                'booking.bookingDuration',
                'booking.totalPrice',
                'booking.created_at',
                'vehicles.model as vehicleModel',
                'vehicles.vehicleType',
                'vehicles.plateNumber as plateNo'
            )
            ->whereYear('booking.created_at', $year)
            ->orderBy('booking.created_at', 'desc')
            ->limit(5);

        // Apply filters if set
        if ($faculty || $college) {
            $recentBookingsQuery->join('users', 'booking.userID', '=', 'users.userID')
                               ->join('customer', 'users.userID', '=', 'customer.userID');
            
            if ($faculty) $recentBookingsQuery->where('customer.faculty', $faculty);
            if ($college) $recentBookingsQuery->where('customer.college', $college);
        }
        
        if ($vehicleType) {
            $recentBookingsQuery->where('vehicles.vehicleType', $vehicleType);
        }

        $recentBookings = $recentBookingsQuery->get();

        return view('admin.reporting', compact(
            'statusCounts', 'rewardStats', 'recentBookings', 
            'year', 'month', 'view', 'faculty', 'college', 'vehicleType',
            'facultyDistribution', 'collegeDistribution',
            'filteredFacultyCount', 'filteredCollegeCount',
            'chartData', 'chartLabels', 'reportStats'
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