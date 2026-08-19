<?php

namespace App\Channels;

use App\Services\FcmService;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    protected FcmService $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Send the given notification via FCM channel.
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $tokens = $notifiable->routeNotificationForFcm($notification);
        if (empty($tokens)) {
            return;
        }

        $fcmData = $notification->toFcm($notifiable);
        
        $title = $fcmData['title'] ?? 'New Notification';
        $body = $fcmData['body'] ?? '';
        $data = $fcmData['data'] ?? [];

        $this->fcmService->sendToTokens($tokens, $title, $body, $data);
    }
}
