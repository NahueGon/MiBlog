<?php

namespace App\Traits;

trait SanitizerTrait
{
    public function sanitize(array $data, bool $escapeHtml = true): array
    {
        $clean = [];

        foreach ($data as $key => $value) {
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $key)) continue;

            if (is_array($value)) {
                $clean[$key] = $this->sanitize($value, $escapeHtml);
            } else {
                $value = trim($value);
                $value = preg_replace('/\s+/', ' ', $value);
                $value = str_replace("\0", '', $value);
                $value = substr($value, 0, 100);

                switch ($key) {
                    case 'email':
                        $value = strtolower($value);
                        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                        break;
                    case 'name':
                    case 'lastname':
                        $value = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚ\s]/u', '', $value);
                        $value = ucwords(mb_strtolower($value, 'UTF-8'));
                        break;
                }

                $clean[$key] = $escapeHtml ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
            }
        }

        return $clean;
    }
}
