<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function addDailyLogManager(Request $request)
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
        $dailyLog->user_id = Auth::id(); // get id of currently logged in user
        $dailyLog->save();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/daillog_proof/', $filename);
            $dailyLog->foto = $filename;
            $dailyLog->save(); // Save the model again after assigning the foto field
        }

        return response()->json(['message' => 'Daily log added successfully', 'data' => $dailyLog], 201);
    }
}
