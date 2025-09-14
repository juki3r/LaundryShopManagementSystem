<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function report(Request $request)
    {
        $period = $request->get('period', 'today'); // default to today

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        switch ($period) {
            case 'weekly':
                $startDate = $startOfWeek;
                $endDate = Carbon::now();
                $label = "This Week";
                break;
            case 'monthly':
                $startDate = $startOfMonth;
                $endDate = Carbon::now();
                $label = "This Month";
                break;
            default:
                $startDate = $today;
                $endDate = $today;
                $label = "Today";
                break;
        }

        $orders = DB::table('orders')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalIncome = $orders->sum('total');

        return view('reports.index', compact('orders', 'totalIncome', 'startDate', 'endDate', 'label', 'period'));
    }
}
