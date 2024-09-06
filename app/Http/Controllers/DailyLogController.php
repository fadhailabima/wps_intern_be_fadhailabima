<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DailyLogController extends Controller
{
    public function addDailyLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required|string',
            'date' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dailyLog = new DailyLog();
        $dailyLog->activity = $request->activity;
        $dailyLog->date = $request->date;
        $dailyLog->status = 'Pending'; // set status to 'pending'
        $dailyLog->user_id = Auth::id(); // get id of currently logged in user
        $dailyLog->save();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/dailylog_proof/', $filename);
            $dailyLog->foto = $filename;
            $dailyLog->save(); // Save the model again after assigning the foto field
        }

        return response()->json(['message' => 'Daily log added successfully', 'data' => $dailyLog], 201);
    }

    public function updateStatusDailyLog(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:Accept,Decline',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dailyLog = DailyLog::find($id);

        if (!$dailyLog) {
            return response()->json(['message' => 'Daily log not found'], 404);
        }

        $dailyLog->status = $request->status;
        $dailyLog->save();

        return response()->json(['message' => 'Status updated successfully', 'data' => $dailyLog], 200);
    }

    public function getDailyLogByUser()
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
