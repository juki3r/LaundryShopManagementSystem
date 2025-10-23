<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function report(Request $request)
    {
        $tz = 'Asia/Manila';
        $period = $request->get('period', 'today');
        $from = $request->get('from');
        $to = $request->get('to');
        $serviceType = $request->get('service_type'); // NEW

        // Base query
        $query = DB::table('orders')->where('claimed', 'Yes');

        // Handle custom date range
        if ($from && $to) {
            $startDate = Carbon::parse($from, $tz)->startOfDay();
            $endDate = Carbon::parse($to, $tz)->endOfDay();
            $label = "Custom Range";
        } else {
            switch ($period) {
                case 'weekly':
                    $startDate = Carbon::now($tz)->startOfWeek();
                    $endDate = Carbon::now($tz)->endOfDay();
                    $label = "This Week";
                    break;
                case 'monthly':
                    $startDate = Carbon::now($tz)->startOfMonth();
                    $endDate = Carbon::now($tz)->endOfDay();
                    $label = "This Month";
                    break;
                default:
                    $startDate = Carbon::now($tz)->startOfDay();
                    $endDate = Carbon::now($tz)->endOfDay();
                    $label = "Today";
                    break;
            }
        }

        // Convert to UTC for DB query
        $startUtc = $startDate->copy()->timezone('UTC');
        $endUtc = $endDate->copy()->timezone('UTC');

        // Apply date range
        $query->whereBetween('created_at', [$startUtc, $endUtc]);

        // Apply service type filter if selected
        if ($serviceType && $serviceType !== 'all') {
            $query->where('service_type', $serviceType);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $totalIncome = $orders->sum('total');

        // Get distinct service types for dropdown
        $serviceTypes = DB::table('orders')
            ->select('service_type')
            ->distinct()
            ->pluck('service_type');

        return view('reports.index', compact(
            'orders',
            'totalIncome',
            'startDate',
            'endDate',
            'label',
            'period',
            'from',
            'to',
            'serviceType',
            'serviceTypes'
        ));
    }
}
