<?php

namespace App\Traits;

trait TimeAgoTrait
{
    private function getTimeAgo(string $datetime): string
    {
        $date = new \DateTime($datetime);
        $now = new \DateTime();

        $diff = $date->diff($now);

        if ($diff->d > 0) {
            return "Hace {$diff->d} d";
        } elseif ($diff->h > 0) {
            return "Hace {$diff->h} h";
        } elseif ($diff->i > 0) {
            return "Hace {$diff->i} m";
        } else {
            return "Hace unos segundos";
        }
    }
}
