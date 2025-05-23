<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Animal;
use App\Models\AdoptionRule;
use App\Models\ShelterInfo;

class CacheService
{
    const CACHE_TTL = 3600; // 1 година

    public static function getAnimals()
    {
        return Cache::remember('animals', self::CACHE_TTL, function () {
            return Animal::with('photos')->get();
        });
    }

    public static function getAdoptionRules()
    {
        return Cache::remember('adoption_rules', self::CACHE_TTL, function () {
            return AdoptionRule::all();
        });
    }

    public static function getShelterInfo()
    {
        return Cache::remember('shelter_info', self::CACHE_TTL, function () {
            return ShelterInfo::first();
        });
    }

    public static function clearAnimalCache()
    {
        Cache::forget('animals');
    }

    public static function clearAdoptionRulesCache()
    {
        Cache::forget('adoption_rules');
    }

    public static function clearShelterInfoCache()
    {
        Cache::forget('shelter_info');
    }

    public static function clearAllCache()
    {
        Cache::flush();
    }
} 