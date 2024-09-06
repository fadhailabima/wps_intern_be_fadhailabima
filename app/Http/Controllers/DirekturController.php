<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DirekturController extends Controller
{
    public function getDailyLogsByManager()
    {
        $dailyLogs = DB::table('daily_logs')
            ->join('users', 'daily_logs.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('divisis', 'users.divisi_id', '=', 'divisis.id')
            ->where('roles.nama_role', 'Manager')
            ->select('daily_logs.*', 'users.nama as nama_user', 'divisis.nama_divisi')
            ->get();

        $dailyLogs = $dailyLogs->map(function ($dailyLog) {
            $dailyLog->image_url = $dailyLog->foto ? url('storage/dailylog_proof/' . $dailyLog->foto) : null;
            return $dailyLog;
        });

        return response()->json(['data' => $dailyLogs], 200);
    }
}
