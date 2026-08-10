@extends('layouts.pos', ['activeNav' => 'inventory'])

@section('title', __('inventory.itemDetail').' · '.__('brand.name'))
@section('body-class', 'inventory-detail-page')
@section('main-class', 'inventory-detail-workspace')

@push('styles')
<style>
    .inventory-detail-workspace { padding: 20px; }
    .inventory-detail-title h1 { margin: 0; font-size: 26px; }
    .inventory-detail-title p { margin: 4px 0 14px; color: var(--pos-muted); font-size: 12px; }
    .inventory-detail-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-block-end: 10px;
    }
    .inventory-detail-back,
    .inventory-detail-chip {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        padding-inline: 12px;
        border: 1px solid var(--pos-line);
        border-radius: 8px;
        color: var(--pos-text);
        background: var(--pos-panel);
    }
    .inventory-detail-back { font-size: 12px; text-decoration: none; }
    .inventory-detail-chip { font-size: 13px; font-weight: 700; }
    .inventory-detail-description {
        margin-block-end: 10px;
        padding: 10px 12px;
        border: 1px solid var(--pos-line);
        border-radius: 8px;
        background: var(--pos-panel);
        font-size: 12px;
    }
    .inventory-detail-panel {
        width: min(620px, 100%);
        overflow-x: auto;
        border: 1px solid var(--pos-line);
        border-radius: 12px;
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }
    .inventory-detail-panel table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .inventory-detail-panel th,
    .inventory-detail-panel td {
        padding: 10px;
        border-block-end: 1px solid var(--pos-line);
        border-inline-end: 1px solid var(--pos-line);
        text-align: center;
    }
    .inventory-detail-panel th:first-child,
    .inventory-detail-panel td:first-child { text-align: start; }
    .inventory-detail-panel th {
        color: var(--pos-muted);
        background: var(--pos-panel-2);
        font-size: 11px;
    }
    .inventory-detail-panel td.low { color: var(--pos-danger); }
    .inventory-detail-panel tr:last-child td {
        background: var(--pos-panel-2);
        font-weight: 700;
    }
</style>
@endpush

@section('content')
    <header class="inventory-detail-title">
        <h1>{{ __('inventory.productList') }}</h1>
        <p>{{ __('inventory.manageProducts') }}</p>
    </header>

    <div class="inventory-detail-header">
        <a class="inventory-detail-back" href="{{ route('inventory.index') }}">{{ __('inventory.back') }}</a>
        <div class="inventory-detail-chip">{{ $product['name'] }}</div>
    </div>

    <div class="inventory-detail-description">
        {{ __('inventory.sizes') }}: {{ implode(' - ', $detail['sizes']) }}<br>
        {{ __('inventory.colors') }}: {{ implode(' - ', $detail['colors']) }}
    </div>

    <section class="inventory-detail-panel">
        <table>
            <thead>
                <tr>
                    <th>{{ __('inventory.size') }}</th>
                    @foreach ($detail['colors'] as $color)
                        <th>{{ $color }}</th>
                    @endforeach
                    <th>{{ __('inventory.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $colorTotals = array_fill(0, count($detail['colors']), 0);
                    $grandTotal = 0;
                @endphp
                @foreach ($detail['matrix'] as $size => $values)
                    @php
                        $rowTotal = array_sum($values);
                        $grandTotal += $rowTotal;
                        foreach ($values as $i => $value) {
                            $colorTotals[$i] += $value;
                        }
                    @endphp
                    <tr>
                        <td>{{ $size }}</td>
                        @foreach ($values as $value)
                            <td class="{{ $value <= 2 ? 'low' : '' }}">{{ $value }}</td>
                        @endforeach
                        <td>{{ $rowTotal }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>{{ __('inventory.total') }}</td>
                    @foreach ($colorTotals as $total)
                        <td>{{ $total }}</td>
                    @endforeach
                    <td>{{ $grandTotal }}</td>
                </tr>
            </tbody>
        </table>
    </section>
@endsection
