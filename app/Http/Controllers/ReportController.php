<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function reportingIndex(Request $request)
{$year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $view = $request->get('view', 'monthly');

        if ($view === 'daily') {
            // Get data for each day of the selected month
            $results = DB::table('payment')
                ->select(
                    DB::raw('DAY(paymentDate) as day'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as sales_count')
                )
                ->whereYear('paymentDate', $year)
                ->whereMonth('paymentDate', $month)
                ->where('paymentStatus', 'completed')
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $chartLabels = range(1, $daysInMonth);
            $chartData = array_fill(0, $daysInMonth, 0);
            
            foreach ($results as $data) {
                $chartData[$data->day - 1] = (float) $data->total;
            }
            
            $reportStats = $results; 
        } else {
            // Default Monthly Logic
            $results = DB::table('payment')
                ->select(
                    DB::raw('MONTH(paymentDate) as month_num'),
                    DB::raw('MONTHNAME(paymentDate) as month_name'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as sales_count')
                )
                ->whereYear('paymentDate', $year)
                ->where('paymentStatus', 'completed')
                ->groupBy('month_num', 'month_name')
                ->orderBy('month_num')
                ->get();

            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chartData = array_fill(0, 12, 0);
            
            foreach ($results as $data) {
                $chartData[$data->month_num - 1] = (float) $data->total;
            }
            
            $reportStats = $results;
        }

        return view('admin.reporting', compact('chartData', 'chartLabels', 'reportStats', 'year', 'month', 'view'));
    }
}