<?php

namespace App\Traits;

trait TimeAgoTrait
{
    private function getTimeAgo(string $datetime): string
    {
        $timezone = new \DateTimeZone('America/Argentina/Buenos_Aires');

        $date = new \DateTime($datetime, $timezone);
        $now = new \DateTime('now', $timezone);

        $diff = $date->diff($now);

        if ($diff->d > 0) {
            return "Hace {$diff->d} d";
        } elseif ($diff->h > 0) {
            return "Hace {$diff->h} h";
        } elseif ($diff->i > 0) {
            return "Hace {$diff->i} m";
        } else {
            return "Ahora";
        }
    }
}
