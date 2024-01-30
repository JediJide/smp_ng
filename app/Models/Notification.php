<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    public function getMyNotification($user_id): Collection
    {
        return DB::table('notifications')
        ->join('notification_messages', 'notification_messages.id', '=', 'notifications.notification_message_id')
        ->where('notifications.user_id', '=', $user_id)
        ->select('notifications.id', 'notification_messages.old_value', 'notification_messages.new_value', 'notifications.created_at', 'notifications.updated_at', 'notifications.bread_crumb', 'notifications.type')
        ->get();
    }

    public function getMyAllNotifications(): Collection
    {
        return DB::table('notifications')
        ->join('notification_messages', 'notification_messages.id', '=', 'notifications.notification_message_id')
        ->select('notifications.id', 'notifications.notification_message_id', 'notification_messages.old_value', 'notification_messages.new_value', 'notifications.created_at', 'notifications.bread_crumb', 'notifications.type')
        ->get();
    }

    public function getNotificationById($id): Collection
    {
        return DB::table('notifications')
        ->join('notification_messages', 'notification_messages.id', '=', 'notifications.notification_message_id')
        ->where('notifications.id', '=', $id)
        ->select('notifications.id', 'notifications.notification_message_id', 'notification_messages.old_value', 'notification_messages.new_value', 'notifications.created_at', 'notifications.updated_at', 'notifications.bread_crumb', 'notifications.type')
        ->get();
    }

    public function getNoticationMessagesById($id): Collection
    {
        return DB::table('notification_messages')
        ->where('id', '=', $id)
        ->get();
    }

    public function deleteNotification($id)
    {
        // return DB::table('notifications')
        // ->where('id','=',$id)
        // ->forceDelete();

      // return DB::delete('delete from notifications where id = ?',[$id]);
    }

    public function deleteNotificationMessage($id): int
    {
        return DB::delete('delete from notification_messages where id = ?', [$id]);
        // return DB::table('notification_messages')
        // ->where('id','=', $id)
        // ->forceDelete();
    }
}
