<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class RangeSlider extends Field
{
    protected string $view = 'forms.components.range-slider';

    protected int|float $minValue = 0;
    protected int|float $maxValue = 100;
    protected int|float $step = 1;

    public function minValue(int|float $value): static
    {
        $this->minValue = $value;
        return $this;
    }

    public function maxValue(int|float $value): static
    {
        $this->maxValue = $value;
        return $this;
    }

    public function step(int|float $value): static
    {
        $this->step = $value;
        return $this;
    }

    public function getMinValue(): int|float
    {
        return $this->minValue;
    }

    public function getMaxValue(): int|float
    {
        return $this->maxValue;
    }

    public function getStep(): int|float
    {
        return $this->step;
    }
}
