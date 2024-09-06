<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{

    public function getDailyLogsByUser()
    {
        $user = Auth::user();

        $dailyLogs = DailyLog::where('user_id', $user->id)->get();

        $dailyLogs->map(function ($dailyLog) {
            $dailyLog->image_url = $dailyLog->foto ? url('storage/dailylog_proof/' . $dailyLog->foto) : null;
            return $dailyLog;
        });

        return response()->json(['data' => $dailyLogs], 200);
    }
}
