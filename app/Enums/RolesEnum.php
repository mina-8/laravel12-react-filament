<?php

namespace App\Enums;

enum RolesEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    // case WRITER = 'writer';
    case CRM = 'crm';
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            // $out[$case->value] = $case->name;
            $out[$case->value] = __('filament-panels::resources/pages/admin.roles.' . $case->value);
        }

        return $out;
    }
}
