<?php

namespace App\Traits;

use App\Core\{ ValidationMessage, Notification };

trait FlashMessageTrait
{
    protected function getValidationMessages(): array
    {
        $validationMessages = ValidationMessage::all();

        $errors = [
            'name' => [],
            'lastname' => [],
            'email' => [],
            'password' => [],
            'repeat_password' => [],
            'old_password' => [],
            'new_password' => [],
            'fields' => [],
            'credentials' => [],
        ];
        
        foreach ($validationMessages as $field => $messages) {
            if (isset($errors[$field]) && is_array($messages)) {
                foreach ($messages as $message) {
                    $errors[$field][] = $message;
                }
            }
        }

        return $errors;
    }

    protected function getNotificationMessages(): array
    {
        $notificationMessages = Notification::all();

        $messages = [
            'success' => [],
            'error' => [],
            'info' => [],
        ];
        
        foreach ($notificationMessages as $type => $messagesArray) {
            if (isset($messages[$type]) && is_array($messagesArray)) {
                foreach ($messagesArray as $message) {
                    $messages[$type][] = $message;
                }
            }
        }

        return $messages;
    }
}
