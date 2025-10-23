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

        // Default
        $startDate = null;
        $endDate = null;
        $label = '';

        if ($from && $to) {
            // Custom range filter
            $startDate = Carbon::parse($from, $tz)->startOfDay();
            $endDate = Carbon::parse($to, $tz)->endOfDay();
            $label = "Custom Range";
            $orders = DB::table('orders')
                ->where('claimed', 'Yes')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
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
                default: // today
                    $startDate = Carbon::now($tz)->startOfDay();
                    $endDate = Carbon::now($tz)->endOfDay();
                    $label = "Today";
                    break;
            }

            // Convert to UTC before querying
            $startUtc = $startDate->copy()->timezone('UTC');
            $endUtc = $endDate->copy()->timezone('UTC');

            $orders = DB::table('orders')
                ->where('claimed', 'Yes')
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $totalIncome = $orders->sum('total');

        return view('reports.index', compact(
            'orders',
            'totalIncome',
            'startDate',
            'endDate',
            'label',
            'period',
            'from',
            'to'
        ));
    }
}
