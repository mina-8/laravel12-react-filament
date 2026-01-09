<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class Map extends Field
{
    protected string $view = 'forms.components.map';

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (Map $component, $state) {
            $component->state($state ?? []);
        });
    }
}
