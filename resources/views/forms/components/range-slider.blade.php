<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        min: {{ $getMinValue() }},
        max: {{ $getMaxValue() }},
        step: {{ $getStep() }}
    }">
        <input
            type="range"
            x-model="state"
            :min="min"
            :max="max"
            :step="step"
            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
        />
        <div class="flex justify-between text-xs text-gray-500 mt-1">
            <span x-text="min"></span>
            <span x-text="state"></span>
            <span x-text="max"></span>
        </div>
    </div>
</x-dynamic-component>
