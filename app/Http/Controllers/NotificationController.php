<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request){
        $notifications = $request->user()->notifications()->latest()->paginate(10);

        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'لا يوجد إشعارات',
                'data' => [],
            ]);
        }

        return NotificationResource::collection($notifications);
    }


    public function markAllAsRead(Request $request){

        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'تم تحديد جميع الإشعارات كمقروءة.'
        ]);
    }


    public function deleteAllNotifications(Request $request){

        $request->user()->notifications()->delete();

        return response()->json([
            'message' => 'تم حذف جميع الإشعارات بنجاح.'
        ]);
    }


}
