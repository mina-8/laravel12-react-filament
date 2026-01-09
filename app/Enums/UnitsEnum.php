<?php

namespace App\Enums;

enum UnitsEnum : string
{
    case KG = 'kg';
    case LITER = 'liter';
    case PIECE = 'piece';

    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            // $out[$case->value] = $case->name;
            $out[$case->value] = __('filament-panels::resources/pages/ourservice.fields.types.' . $case->value);
        }

        return $out;
    }
}
