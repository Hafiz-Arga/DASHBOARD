@if ($yoy === null)
    <p class="yoy-indicator yoy-neutral">
        <span class="yoy-icon">—</span>
        <span>Tidak tersedia vs LY</span>
    </p>
@else
    @php
        $isIncrease = $yoy > 0;

        // Negative Profit Orders memiliki aturan terbalik.
        $isGood = $inverse ? !$isIncrease : $isIncrease;

        $color = $yoy == 0 ? 'yoy-neutral' : ($isGood ? 'good' : 'bad');

        $icon = $isIncrease ? '↑' : ($yoy < 0 ? '↓' : '→');

        $sign = $yoy > 0 ? '+' : ($yoy < 0 ? '-' : '');
    @endphp

    <p class="yoy-indicator {{ $color }}">
        <span class="yoy-icon">{{ $icon }}</span>
        <span>{{ $sign }}{{ number_format(abs($yoy), 1) }}% vs LY</span>
    </p>
@endif
