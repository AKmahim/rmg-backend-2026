<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SiteView;
use Illuminate\Http\Request;

class SiteViewStatisticsController extends Controller
{
    //
    public function statistics()
    {
        $today = date('Y-m-d');
        $lastTenDays = [];
        $lastTenDaysContent = [];

        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime($today.' -'.$i.' days'));
            $lastTenDays[] = $date;
            $ContentCount = SiteView::whereDate('created_at', $date)->count();
            $lastTenDaysContent[] = $ContentCount;
        }

        return response()->json([
            'last_ten_days' => array_reverse($lastTenDays),
            'last_ten_days_content' => array_reverse($lastTenDaysContent),
        ], 200);
    }

    // site view index
    public function index(Request $request)
    {
        $query = SiteView::query();

        if ($request->has('start_date') && $request->start_date != null) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != null) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Clone query for stats to avoid affecting pagination
        $statsQuery = clone $query;
        $totalViews = $statsQuery->count();
        $uniqueVisitors = $statsQuery->distinct('ip_address')->count('ip_address');
        
        // Calculate top country
        $topCountry = $statsQuery->select('country', \DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->first();
            
        $topCountryName = $topCountry ? $topCountry->country : 'N/A';

        $siteViews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.site-view.index', compact('siteViews', 'totalViews', 'uniqueVisitors', 'topCountryName'));
    }
}
