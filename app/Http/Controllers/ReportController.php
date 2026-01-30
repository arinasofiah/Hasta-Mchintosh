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

            // Recent bookings with vehicle details - FIXED VERSION WITH DATES
            $recentBookingsQuery = DB::table('booking')
                ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                ->select(
                    'booking.bookingID',
                    'booking.booking_code',
                    'booking.bookingStatus',
                    'booking.bookingDuration',
                    'booking.totalPrice',
                    'booking.created_at',
                    'booking.startDate',      // ADDED
                    'booking.endDate',        // ADDED
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

        // 2. Base Query for INCOME (Monthly/Daily) - USING PAYMENT TABLE
        $incomeQuery = DB::table('payment')
            ->where('paymentStatus', 'completed');

        if ($view === 'daily') {
            $results = (clone $incomeQuery)
                ->select(
                    DB::raw('DAY(paymentDate) as day'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as sales_count'),
                    DB::raw('AVG(amount) as average_sale')
                )
                ->whereYear('paymentDate', $year)
                ->whereMonth('paymentDate', $month)
                ->groupBy('day')->orderBy('day')->get();

            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $chartLabels = range(1, $daysInMonth);
            $chartData = array_fill(0, $daysInMonth, 0);
            foreach ($results as $data) { 
                $chartData[$data->day - 1] = (float) $data->total; 
            }
            $reportStats = $results;

        } else {
            // Monthly logic
            $results = (clone $incomeQuery)
                ->select(
                    DB::raw('MONTH(paymentDate) as month_num'),
                    DB::raw('MONTHNAME(paymentDate) as month_name'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as sales_count'),
                    DB::raw('AVG(amount) as average_sale')
                )
                ->whereYear('paymentDate', $year)
                ->groupBy('month_num', 'month_name')->orderBy('month_num')->get();

            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chartData = array_fill(0, 12, 0);
            foreach ($results as $data) { 
                $chartData[$data->month_num - 1] = (float) $data->total; 
            }
            $reportStats = $results;
        }

        return view('admin.reporting', compact('chartData', 'chartLabels', 'reportStats', 'year', 'month', 'view'));
    }

    public function exportReport(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $view = $request->get('view', 'monthly');

        // Fetch data from payment table
        if ($view === 'daily') {
            $fileName = "Daily_Payment_Report_{$month}_{$year}.csv";
            $data = DB::table('payment')
                ->select(
                    DB::raw('DAY(paymentDate) as label'),
                    DB::raw('COUNT(*) as transactions'),
                    DB::raw('SUM(amount) as total_revenue'),
                    DB::raw('AVG(amount) as avg_transaction')
                )
                ->whereYear('paymentDate', $year)
                ->whereMonth('paymentDate', $month)
                ->where('paymentStatus', 'completed')
                ->groupBy('label')->orderBy('label')->get();
            
            $header = ['Day', 'No of Transactions', 'Total Revenue (RM)', 'Average Transaction (RM)'];
        } else {
            $fileName = "Monthly_Payment_Report_{$year}.csv";
            $data = DB::table('payment')
                ->select(
                    DB::raw('MONTHNAME(paymentDate) as label'),
                    DB::raw('COUNT(*) as transactions'),
                    DB::raw('SUM(amount) as total_revenue'),
                    DB::raw('AVG(amount) as avg_transaction')
                )
                ->whereYear('paymentDate', $year)
                ->where('paymentStatus', 'completed')
                ->groupBy(DB::raw('MONTH(paymentDate)'), 'label')
                ->orderBy(DB::raw('MONTH(paymentDate)'))->get();
            
            $header = ['Month', 'No of Transactions', 'Total Revenue (RM)', 'Average Transaction (RM)'];
        }

        // Generate CSV
        $callback = function() use ($data, $header) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->label, 
                    $row->transactions, 
                    number_format($row->total_revenue ?? 0, 2),
                    number_format($row->avg_transaction ?? 0, 2)
                ]);
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
    
    /**
     * Additional method to get payment breakdown by payment method
     */
    public function getPaymentMethodBreakdown(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        $breakdown = DB::table('payment')
            ->select(
                'paymentMethod',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('AVG(amount) as avg_revenue')
            )
            ->whereYear('paymentDate', $year)
            ->whereMonth('paymentDate', $month)
            ->where('paymentStatus', 'completed')
            ->groupBy('paymentMethod')
            ->get();
            
        return response()->json($breakdown);
    }
    
    /**
     * Additional method to get payment summary with booking details
     */
    public function getPaymentSummary(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        $summary = DB::table('payment')
            ->join('booking', 'payment.bookingID', '=', 'booking.bookingID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select(
                'payment.paymentID',
                'payment.amount',
                'payment.paymentMethod',
                'payment.paymentDate',
                'booking.booking_code',
                'booking.totalPrice as booking_total',
                'vehicles.model',
                'vehicles.vehicleType'
            )
            ->whereYear('payment.paymentDate', $year)
            ->whereMonth('payment.paymentDate', $month)
            ->where('payment.paymentStatus', 'completed')
            ->orderBy('payment.paymentDate', 'desc')
            ->limit(20)
            ->get();
            
        return response()->json($summary);
    }
}