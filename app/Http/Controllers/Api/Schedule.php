<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Schedule as ScheduleModel;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Schedule extends Controller
{
    public function getSchedule()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(['success' => false, 'msg' => 'unauthrized']);
        }
        $dep_id = $user->dep_id;

        $schedule = ScheduleModel::where('dep_id', $dep_id)->first();

        if ($schedule) {
            return response()->json(['msg' => 'Schedule found.', 'data' => $schedule], 200);
        } else {
            return response()->json(['msg' => 'Schedule not found.'], 404);
        }
       
    }
}
