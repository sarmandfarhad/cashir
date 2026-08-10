@extends('layouts.pos', ['activeNav' => 'sales'])

@section('title', __('sales.transactionHistory').' · '.__('brand.name'))
@section('body-class', 'sales-page')
@section('main-class', 'sales-workspace')

@push('styles')
<style>
    .sales-workspace { padding: 24px; }
    .sales-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-block-end: 24px;
    }
    .sales-title h1 { margin: 0; font-size: 24px; letter-spacing: -.02em; }
    .sales-title p { margin: 5px 0 0; color: var(--pos-muted); font-size: 13px; }
    .sales-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .sales-bell,
    .sales-meta-card {
        min-height: 44px;
        border: 1px solid var(--pos-line);
        border-radius: 14px;
        color: var(--pos-text);
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }
    .sales-bell {
        width: 44px;
        display: grid;
        place-items: center;
        cursor: pointer;
    }
    .sales-meta-card { display: flex; align-items: center; gap: 10px; padding: 8px 16px; }
    .sales-meta-card small,
    .sales-meta-card strong { display: block; }
    .sales-meta-card small { color: var(--pos-muted); font-size: 10px; text-transform: uppercase; }
    .sales-meta-card strong { font-size: 12px; }
    .sales-avatar {
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--pos-primary-soft);
    }
    .sales-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-block-end: 24px;
    }
    .sales-stat {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px;
        border: 1px solid var(--pos-line);
        border-radius: 16px;
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }
    .sales-stat--green { border-color: color-mix(in srgb, var(--pos-success) 25%, var(--pos-line)); background: color-mix(in srgb, var(--pos-success) 8%, var(--pos-panel)); }
    .sales-stat--blue { border-color: color-mix(in srgb, var(--pos-primary) 25%, var(--pos-line)); background: var(--pos-primary-soft); }
    .sales-stat__icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--pos-panel);
        font-size: 20px;
    }
    .sales-stat label { display: block; color: var(--pos-muted); font-size: 13px; }
    .sales-stat strong { display: block; margin-block-start: 4px; color: var(--pos-primary); font-size: 22px; }
    .sales-toolbar { margin-block-end: 20px; }
    .sales-search { position: relative; width: min(440px, 100%); }
    .sales-search span {
        position: absolute;
        inset-inline-start: 14px;
        inset-block-start: 50%;
        transform: translateY(-50%);
        color: var(--pos-muted);
    }
    .sales-search input {
        width: 100%;
        height: 38px;
        padding-inline: 38px 14px;
        border: 1px solid var(--pos-line);
        border-radius: 10px;
        outline: 0;
        color: var(--pos-text);
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }
    .sales-table {
        overflow-x: auto;
        border: 1px solid var(--pos-line);
        border-radius: 14px;
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }
    .sales-table table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .sales-table th,
    .sales-table td {
        padding: 14px 16px;
        border-block-end: 1px solid var(--pos-line);
        text-align: start;
        white-space: nowrap;
    }
    .sales-table th {
        color: var(--pos-muted);
        background: var(--pos-panel-2);
        font-size: 11px;
        text-transform: uppercase;
    }
    .sales-table tbody tr:hover { background: var(--pos-panel-2); }
    .transaction-id { font-weight: 600; }
    .payment-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        color: var(--pos-primary);
        background: var(--pos-primary-soft);
        font-size: 11px;
        font-weight: 700;
    }
    .payment-badge.cash { color: var(--pos-success); background: color-mix(in srgb, var(--pos-success) 12%, var(--pos-panel)); }
    .sales-actions { display: flex; gap: 12px; }
    .sales-action { border: 0; color: var(--pos-primary); background: transparent; cursor: pointer; font-size: 16px; }
    .sales-empty { padding: 30px; color: var(--pos-muted); text-align: center; font-style: italic; }
    @media (max-width: 760px) {
        .sales-workspace { padding: 16px; }
        .sales-head { align-items: stretch; flex-direction: column; }
        .sales-stats { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <header class="sales-head">
        <div class="sales-title">
            <h1>{{ __('sales.transactionHistory') }}</h1>
            <p>{{ __('sales.historySubtitle') }}</p>
        </div>
        <div class="sales-meta">
            <button class="sales-bell" type="button" aria-label="{{ __('sales.notifications') }}">🔔</button>
            <div class="sales-meta-card">
                <div><small>{{ __('common.time') }}</small><strong id="live-clock">--:--:--</strong></div>
            </div>
            <div class="sales-meta-card">
                <div class="sales-avatar">●</div>
                <div><small>{{ __('common.cashier') }}</small><strong>{{ auth()->user()->name ?? __('common.cashier') }}</strong></div>
            </div>
        </div>
    </header>

    @php
        $totalSalesAmount = 0;
        foreach ($transactions as $trx) {
            $totalSalesAmount += $trx['total_payment'];
        }
    @endphp
    <section class="sales-stats">
        <div class="sales-stat sales-stat--green">
            <div class="sales-stat__icon">🛒</div>
            <div><label>{{ __('sales.totalTransactions') }}</label><strong>{{ count($transactions) }}</strong></div>
        </div>
        <div class="sales-stat sales-stat--blue">
            <div class="sales-stat__icon">💵</div>
            <div><label>{{ __('sales.totalSales') }}</label><strong>IQD {{ number_format($totalSalesAmount, 0, '.', '.') }}</strong></div>
        </div>
    </section>

    <div class="sales-toolbar">
        <label class="sales-search">
            <span aria-hidden="true">⌕</span>
            <input type="search" id="sales-search" placeholder="{{ __('sales.searchPlaceholder') }}" aria-label="{{ __('sales.search') }}">
        </label>
    </div>

    <section class="sales-table">
        <table>
            <thead>
                <tr>
                    <th>{{ __('sales.transactionId') }}</th>
                    <th>{{ __('sales.dateTime') }}</th>
                    <th>{{ __('sales.cashierName') }}</th>
                    <th>{{ __('sales.totalItems') }}</th>
                    <th>{{ __('sales.totalPayment') }}</th>
                    <th>{{ __('sales.paymentMethod') }}</th>
                    <th>{{ __('sales.action') }}</th>
                </tr>
            </thead>
            <tbody id="sales-tbody">
                @forelse ($transactions as $trx)
                    <tr data-trx-id="{{ strtolower($trx['id']) }}" data-cashier="{{ strtolower($trx['cashier_name']) }}">
                        <td class="transaction-id">{{ $trx['id'] }}</td>
                        <td>{{ $trx['date_time'] }}</td>
                        <td>{{ $trx['cashier_name'] }}</td>
                        <td>{{ $trx['total_items'] }}</td>
                        <td>IQD {{ number_format($trx['total_payment'], 0, '.', '.') }}</td>
                        <td><span class="payment-badge {{ strtolower($trx['payment_method']) }}">{{ __('payment.'.strtolower($trx['payment_method'])) }}</span></td>
                        <td>
                            <div class="sales-actions">
                                <button class="sales-action" type="button" title="{{ __('sales.viewDetails') }}" onclick="viewDetails('{{ $trx['id'] }}')">👁</button>
                                <button class="sales-action" type="button" title="{{ __('sales.printReceipt') }}" onclick="printReceipt('{{ $trx['id'] }}')">🖨</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="sales-empty">{{ __('sales.noTransactions') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection

@push('scripts')
<script>
    (() => {
        const locale = @json(app()->getLocale() === 'ar' ? 'ar-IQ' : 'en-US');
        const strings = {
            viewDetails: @json(__('sales.viewingDetails')),
            printReceipt: @json(__('sales.printingReceipt')),
        };
        const clock = document.getElementById('live-clock');
        const search = document.getElementById('sales-search');
        const rows = [...document.querySelectorAll('#sales-tbody tr')].filter((row) => !row.querySelector('.sales-empty'));

        const updateClock = () => {
            clock.textContent = new Intl.DateTimeFormat(locale, {
                timeZone: 'Asia/Baghdad',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                weekday: 'short',
            }).format(new Date());
        };
        const applyFilters = () => {
            const query = search.value.trim().toLowerCase();
            rows.forEach((row) => {
                row.hidden = !!query && ![row.dataset.trxId, row.dataset.cashier].some((value) => value.includes(query));
            });
        };

        window.viewDetails = (id) => alert(`${strings.viewDetails} ${id}`);
        window.printReceipt = (id) => alert(`${strings.printReceipt} ${id}`);
        search.addEventListener('input', applyFilters);
        updateClock();
        setInterval(updateClock, 1000);
    })();
</script>
@endpush
