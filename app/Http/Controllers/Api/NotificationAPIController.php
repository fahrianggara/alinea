<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{

    public function index(){
        $notifications = Notification::all()->latest();
        return response()->json(
            new ResResource($notifications, true, "my notification retrieved successfully"),
            200
        );
    }
    public function mynotif()
    {
        $notifications = Notification::find('id', Auth::id());
        return response()->json(
            new ResResource($notifications, true, "my notification retrieved successfully"),
            200
        );
    }

    public function destroyAll(){

        Notification::query()->delete();

        return response()->json(
            new ResResource(null, true, "notifications has delected"),
            200
        );
    }
}
