<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function report(Request $request)
    {
        $tz = 'Asia/Manila'; // PH timezone
        $period = $request->get('period', 'today');

        switch ($period) {
            case 'weekly':
                $startDate = Carbon::now($tz)->startOfWeek();
                $endDate = Carbon::now($tz);
                $label = "This Week";
                $orders = DB::table('orders')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'monthly':
                $startDate = Carbon::now($tz)->startOfMonth();
                $endDate = Carbon::now($tz);
                $label = "This Month";
                $orders = DB::table('orders')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            default: // today
                $today = Carbon::today($tz);
                $startDate = $today;
                $endDate = $today;
                $label = "Today";
                $orders = DB::table('orders')
                    ->whereDate('created_at', $today) // only date part, ignores timezone issues
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }

        $totalIncome = $orders->sum('total');
        return view('reports.index', compact('orders', 'totalIncome', 'startDate', 'endDate', 'label', 'period'));
    }
}
