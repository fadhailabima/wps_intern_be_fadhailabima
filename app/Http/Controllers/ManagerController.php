<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{

    public function getDailyLogStaff()
    {
        $user = Auth::user();

        $dailyLogs = DailyLog::whereHas('user', function ($query) use ($user) {
            $query->where('divisi_id', $user->divisi_id)
                ->where('role_id', '!=', $user->role_id);
        })->get();

        return response()->json(['data' => $dailyLogs], 200);
    }
}
