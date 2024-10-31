<?php

namespace App\Http\Controllers\Backend;

use App\Events\OrderNotification;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notificationCount(){
        $notification=Notification::where('staff_views_all',1)->count();
         event(new OrderNotification($notification));
        return response()->json($notification,200);
    }
}
