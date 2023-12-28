<?php

namespace App\Http\Traits;

use App\Models\CustomNotification;

trait SendNotificationTrait
{
  public function sendNotification($user, $message, $action)
  {
    $insertData = [
      'user_id' => $user,
      'message' => $message,
      'action' => $action,
      'type' => '',
      'is_read' => 0,
      'notify_datetime' => now(),
    ];
    CustomNotification::create($insertData);
  }
}
