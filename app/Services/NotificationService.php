<?php

namespace App\Services;

use App\Models\SiteNotification;
use App\Models\User;

class NotificationService
{
    private const TEMPLATES = [
        // Keys: use snake case
        'franchise_approved' => 'Hi {user_name}, your franchise application has been approved.',
        'franchise_rejected' => 'Hi {user_name}, your franchise application was rejected. Reason: {rejection_reason}',
        'franchise_under_review' => 'Hi {user_name}, your franchise application is under review.',
    ];

    public function sendToUser(User $user, string $templateKey, array $data = []): SiteNotification
    {
        $template = self::TEMPLATES[$templateKey] ?? null;
        if (!$template) {
            throw new \InvalidArgumentException('Unknown template key: ' . $templateKey);
        }

        $rendered = $this->render($template, $data + [
            'user_name' => $user->name,
        ]);

        return SiteNotification::create([
            'user_id' => $user->id,
            'template_key' => $templateKey,
            'message' => $rendered,
            'data' => $data,
        ]);
    }

    public function render(string $template, array $data): string
    {
        return preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($matches) use ($data) {
            $key = $matches[1];
            return isset($data[$key]) ? (string) $data[$key] : '{' . $key . '}';
        }, $template);
    }
}


