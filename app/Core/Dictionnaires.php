<?php

namespace App\Core;

class Dictionnaires
{
    private static array $mois = [
        'janvier' => 'january',
        'février' => 'february',
        'mars' => 'march',
        'avril' => 'april',
        'mai' => 'may',
        'juin' => 'june',
        'juillet' => 'july',
        'août' => 'august',
        'septembre' => 'september',
        'octobre' => 'october',
        'novembre' => 'november',
        'décembre' => 'december',
    ];

    private static array $jours = [
        'lundi' => 'monday',
        'mardi' => 'tuesday',
        'mercredi' => 'wednesday',
        'jeudi' => 'thursday',
        'vendredi' => 'friday',
        'samedi' => 'saturday',
        'dimanche' => 'sunday',
    ];

    public static function getMois(?string $key = null): array|string|null
    {
        if ($key !== null) {
            return self::$mois[$key] ?? null;
        }
        return self::$mois;
    }

    public static function getJours(?string $key = null): array|string|null
    {
        if ($key !== null) {
            return self::$jours[$key] ?? null;
        }
        return self::$jours;
    }

    public static function getMoisFrancais(): array
    {
        return array_keys(self::$mois);
    }

    public static function getJoursFrancais(): array
    {
        return array_keys(self::$jours);
    }
}
