<?php

namespace App\Constants;

class Roles
{
    const ADMIN = 'ADMIN';
    const KADES = 'KADES';
    const SEKDES = 'SEKDES';
    const KAUR_PERENCANAAN = 'KAUR PERENCANAAN';
    const KAUR_KEUANGAN = 'KAUR KEUANGAN';
    const KAUR_TATA_USAHA_UMUM = 'KAUR TATA USAHA & UMUM';

    /**
     * Roles that define KAUR
     */
    const KAUR = [
        self::ADMIN,
        self::KADES,
        self::SEKDES,
        self::KAUR_PERENCANAAN,
        self::KAUR_KEUANGAN,
        self::KAUR_TATA_USAHA_UMUM,
    ];

    /**
     * Check if a role is part of KAUR
     */
    public static function isKaur(string $role): bool
    {
        return in_array($role, self::KAUR);
    }

    /**
     * Get all KAUR roles
     */
    public static function getKaurRoles(): array
    {
        return self::KAUR;
    }
}
