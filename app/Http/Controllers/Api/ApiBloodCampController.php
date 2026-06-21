<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodCamp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiBloodCampController extends Controller
{
    public function upcoming(Request $request)
    {
        $limit = $request->get('limit', 2);

        $bloodCamps = BloodCamp::where('status', 1)
            ->whereDate('camp_date', '>=', Carbon::today())
            ->orderBy('camp_date', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bloodCamps,
        ]);
    }
}
