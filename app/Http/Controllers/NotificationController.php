<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller
{
    private $messaging;

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    public function sendMessage(string $deviceToken, string $title, string $body, ?string $imageUrl = null, ?array $payload = [])
    {
        // $deviceToken = $deviceToken ?? '...';
        $notification = Notification::create($title, $body, $imageUrl);
        $data = [
            'image' => $imageUrl,
            ...$payload,
        ];
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification) // optional
            ->withData($data);

        $this->messaging->send($message);
    }
}
