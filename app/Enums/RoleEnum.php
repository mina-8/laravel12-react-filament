<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPERADMIN = 'super_admin';
    case ADMIN = 'admin';

    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->name;
        }

        return $out;
    }
}
