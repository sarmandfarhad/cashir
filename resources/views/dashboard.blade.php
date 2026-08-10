@extends('layouts.pos', ['activeNav' => 'cashier'])

@section('title', __('pos.cashier').' · '.__('brand.name'))
@section('body-class', 'cashier-page')
@section('main-class', 'cashier-workspace')

@push('styles')
<style>
    .cashier-workspace {
        padding: 18px 20px 24px;
    }

    .cashier-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-block-end: 15px;
    }

    .cashier-head h1 {
        margin: 0;
        font-size: 20px;
        letter-spacing: -0.025em;
    }

    .cashier-head p {
        margin: 4px 0 0;
        color: var(--pos-muted);
        font-size: 11px;
    }

    .cashier-head__meta {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cashier-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        padding: 7px 10px;
        border: 1px solid var(--pos-line);
        border-radius: 9px;
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }

    .cashier-meta span {
        color: var(--pos-primary);
        font-size: 15px;
    }

    .cashier-meta small,
    .cashier-meta strong {
        display: block;
        font-size: 9px;
    }

    .cashier-meta small {
        color: var(--pos-muted);
    }

    .catalog {
        min-height: calc(100vh - 94px);
        padding: 14px;
        border: 1px solid var(--pos-line);
        border-radius: 12px;
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
    }

    .catalog-tools {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-block-end: 12px;
    }

    .product-search {
        width: min(310px, 100%);
        height: 36px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-inline: 11px;
        border: 1px solid var(--pos-line);
        border-radius: 8px;
        color: var(--pos-muted);
        background: var(--pos-panel-2);
    }

    .product-search input {
        width: 100%;
        border: 0;
        outline: 0;
        color: var(--pos-text);
        background: transparent;
        font-size: 10px;
    }

    .filters {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .filters::-webkit-scrollbar {
        display: none;
    }

    .filter-chip {
        flex: 0 0 auto;
        min-height: 31px;
        padding: 6px 12px;
        border: 1px solid var(--pos-line);
        border-radius: 7px;
        color: var(--pos-muted);
        background: var(--pos-panel);
        font-size: 9px;
        cursor: pointer;
    }

    .filter-chip.is-active {
        color: #fff;
        border-color: var(--pos-primary);
        background: var(--pos-primary);
    }

    .catalog-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 12px;
        margin: 13px 1px;
    }

    .catalog-heading h2 {
        margin: 0;
        font-size: 13px;
    }

    .catalog-heading span {
        color: var(--pos-muted);
        font-size: 9px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 11px;
    }

    .product-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid var(--pos-line);
        border-radius: 9px;
        background: var(--pos-panel);
        transition: transform 150ms ease, box-shadow 150ms ease;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--pos-shadow);
    }

    .product-card__image {
        position: relative;
        height: 108px;
        display: grid;
        place-items: center;
        overflow: hidden;
        color: var(--pos-primary);
        background:
            radial-gradient(circle at 60% 20%, color-mix(in srgb, var(--pos-primary) 18%, transparent), transparent 44%),
            var(--pos-soft);
    }

    .product-card__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-card__image svg {
        width: 58px;
        height: 58px;
        opacity: 0.78;
    }

    .product-tag {
        position: absolute;
        inset-block-start: 7px;
        inset-inline-start: 7px;
        max-width: calc(100% - 14px);
        padding: 3px 6px;
        overflow: hidden;
        border-radius: 4px;
        color: var(--pos-primary);
        background: color-mix(in srgb, var(--pos-panel) 90%, transparent);
        font-size: 7px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-card__body {
        padding: 9px;
    }

    .product-card h3 {
        margin: 0 0 4px;
        overflow: hidden;
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-card p {
        margin: 2px 0;
        color: var(--pos-muted);
        font-size: 7.5px;
    }

    .product-card__footer {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 7px;
        margin-block-start: 8px;
    }

    .product-price {
        color: var(--pos-primary);
        font-size: 9px;
        font-weight: 700;
    }

    .product-stock {
        margin-block-start: 2px;
        color: var(--pos-success);
        font-size: 7px;
    }

    .add-product {
        width: 25px;
        height: 25px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 6px;
        color: #fff;
        background: var(--pos-primary);
        cursor: pointer;
    }

    .add-product:disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }

    .checkout-panel {
        position: sticky;
        inset-block-start: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
        gap: 13px;
        padding: 19px 16px;
        overflow-y: auto;
        border-inline-start: 1px solid var(--pos-line);
        background: var(--pos-panel);
    }

    .checkout-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-block-end: 11px;
        border-block-end: 1px solid var(--pos-line);
    }

    .checkout-head h2 {
        margin: 0;
        font-size: 13px;
    }

    .item-count {
        padding: 4px 7px;
        border-radius: 999px;
        color: var(--pos-primary);
        background: var(--pos-primary-soft);
        font-size: 8px;
    }

    .cart-list {
        display: grid;
        gap: 8px;
        max-height: 32vh;
        overflow-y: auto;
    }

    .cart-empty {
        min-height: 150px;
        display: grid;
        place-items: center;
        padding: 18px;
        border: 1px dashed var(--pos-line);
        border-radius: 10px;
        color: var(--pos-muted);
        background: var(--pos-panel-2);
        text-align: center;
        font-size: 10px;
        line-height: 1.55;
    }

    .cart-empty span {
        display: block;
        margin-block-end: 5px;
        color: var(--pos-primary);
        font-size: 28px;
    }

    .cart-item {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 8px;
        padding: 9px;
        border: 1px solid var(--pos-line);
        border-radius: 8px;
        background: var(--pos-panel-2);
    }

    .cart-item h3 {
        margin: 0 0 3px;
        overflow: hidden;
        font-size: 9px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cart-item p {
        margin: 2px 0;
        color: var(--pos-muted);
        font-size: 7px;
    }

    .cart-item__total {
        color: var(--pos-primary);
        font-weight: 700;
    }

    .cart-remove {
        margin-block-start: 4px;
        padding: 0;
        border: 0;
        color: var(--pos-danger);
        background: transparent;
        font-size: 7px;
        cursor: pointer;
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .qty-control button {
        width: 20px;
        height: 20px;
        border: 1px solid var(--pos-line);
        border-radius: 5px;
        color: var(--pos-text);
        background: var(--pos-panel);
        cursor: pointer;
    }

    .qty-control span {
        min-width: 16px;
        text-align: center;
        font-size: 8px;
        font-weight: 700;
    }

    .checkout-calculation {
        display: grid;
        gap: 10px;
        padding-block-start: 3px;
        border-block-start: 1px solid var(--pos-line);
    }

    .calculation-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: var(--pos-muted);
        font-size: 9px;
    }

    .calculation-row strong {
        color: var(--pos-text);
    }

    .discount-control {
        display: grid;
        grid-template-columns: 70px 1fr;
        gap: 6px;
    }

    .discount-control select,
    .discount-control input,
    .payment-input input {
        width: 100%;
        height: 31px;
        padding-inline: 8px;
        border: 1px solid var(--pos-line);
        border-radius: 6px;
        outline: 0;
        color: var(--pos-text);
        background: var(--pos-panel-2);
        font-size: 9px;
    }

    .checkout-total {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 10px;
        padding: 11px;
        border-radius: 8px;
        color: var(--pos-primary);
        background: var(--pos-primary-soft);
    }

    .checkout-total span {
        font-size: 9px;
    }

    .checkout-total strong {
        font-size: 15px;
    }

    .payment-methods > span {
        display: block;
        margin-block-end: 7px;
        color: var(--pos-muted);
        font-size: 9px;
    }

    .payment-methods__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 5px;
    }

    .payment-method {
        min-height: 34px;
        border: 1px solid var(--pos-line);
        border-radius: 6px;
        color: var(--pos-muted);
        background: var(--pos-panel);
        font-size: 8px;
        cursor: pointer;
    }

    .payment-method.is-active {
        color: var(--pos-primary);
        border-color: var(--pos-primary);
        background: var(--pos-primary-soft);
    }

    .checkout-actions {
        display: grid;
        gap: 6px;
        margin-block-start: auto;
    }

    .checkout-button {
        min-height: 37px;
        border: 0;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 700;
        cursor: pointer;
    }

    .checkout-button--primary {
        color: #fff;
        background: var(--pos-primary);
    }

    .checkout-button--primary:disabled {
        cursor: not-allowed;
        opacity: 0.45;
    }

    .checkout-button--danger {
        color: var(--pos-danger);
        border: 1px solid color-mix(in srgb, var(--pos-danger) 35%, var(--pos-line));
        background: var(--pos-danger-soft);
    }

    .modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: none;
        place-items: center;
        padding: 20px;
        background: rgba(6, 14, 29, 0.58);
        backdrop-filter: blur(2px);
    }

    .modal-backdrop.is-open {
        display: grid;
    }

    .payment-modal {
        width: min(470px, 100%);
        overflow: hidden;
        border: 1px solid var(--pos-line);
        border-radius: 11px;
        color: var(--pos-text);
        background: var(--pos-panel);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.28);
    }

    .payment-modal__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-block-end: 1px solid var(--pos-line);
    }

    .payment-modal__head h2 {
        margin: 0;
        font-size: 13px;
    }

    .payment-modal__close {
        border: 0;
        color: var(--pos-muted);
        background: transparent;
        font-size: 20px;
        cursor: pointer;
    }

    .payment-modal__body {
        display: grid;
        gap: 13px;
        padding: 16px;
    }

    .payment-modal__note {
        margin: 0;
        color: var(--pos-muted);
        font-size: 9px;
    }

    .payment-highlight {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 11px;
        border-radius: 8px;
        background: var(--pos-primary-soft);
    }

    .payment-highlight span,
    .payment-input label {
        color: var(--pos-muted);
        font-size: 9px;
    }

    .payment-highlight strong {
        color: var(--pos-primary);
        font-size: 13px;
    }

    .payment-input {
        display: grid;
        gap: 5px;
    }

    .quick-amounts {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 5px;
    }

    .quick-amounts button {
        min-height: 30px;
        border: 1px solid var(--pos-line);
        border-radius: 6px;
        color: var(--pos-primary);
        background: var(--pos-panel-2);
        font-size: 8px;
        cursor: pointer;
    }

    .payment-error {
        display: none;
        margin: 0;
        color: var(--pos-danger);
        font-size: 9px;
    }

    .payment-error.is-visible {
        display: block;
    }

    .payment-modal__actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 7px;
    }

    .payment-modal__actions button {
        min-height: 36px;
        border-radius: 7px;
        font-size: 8px;
        font-weight: 700;
        cursor: pointer;
    }

    .save-print {
        color: #fff;
        border: 1px solid var(--pos-primary);
        background: var(--pos-primary);
    }

    .save-only {
        color: var(--pos-primary);
        border: 1px solid var(--pos-primary);
        background: var(--pos-panel);
    }

    .toast-container {
        position: fixed;
        inset-inline-end: 22px;
        inset-block-end: 22px;
        z-index: 200;
        display: grid;
        gap: 8px;
    }

    .toast {
        min-width: 250px;
        padding: 11px 13px;
        border: 1px solid var(--pos-line);
        border-inline-start: 4px solid var(--pos-success);
        border-radius: 8px;
        color: var(--pos-text);
        background: var(--pos-panel);
        box-shadow: var(--pos-shadow);
        font-size: 10px;
    }

    .toast.is-error {
        border-inline-start-color: var(--pos-danger);
    }

    .toast small {
        display: block;
        margin-block-start: 3px;
        color: var(--pos-muted);
    }

    @media (max-width: 1380px) {
        .product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1180px) {
        .checkout-panel {
            position: static;
            height: auto;
            min-height: 420px;
            border-block-start: 1px solid var(--pos-line);
            border-inline-start: 0;
        }
    }

    @media (max-width: 850px) {
        .product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .catalog-tools,
        .cashier-head {
            align-items: stretch;
            flex-direction: column;
        }

        .product-search {
            width: 100%;
        }
    }

    @media (max-width: 520px) {
        .cashier-workspace {
            padding: 12px;
        }

        .product-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .product-card__image {
            height: 92px;
        }

        .quick-amounts,
        .payment-modal__actions {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>
@endpush

@section('content')
    <header class="cashier-head">
        <div>
            <h1>{{ __('pos.cashier') }}</h1>
            <p>{{ __('pos.subtitle') }}</p>
        </div>
        <div class="cashier-head__meta">
            <div class="cashier-meta">
                <span>◷</span>
                <div><small>{{ __('common.time') }}</small><strong id="live-clock">--:--:--</strong></div>
            </div>
            <div class="cashier-meta">
                <span>●</span>
                <div><small>{{ __('common.operator') }}</small><strong>{{ auth()->user()->name ?? __('common.cashier') }}</strong></div>
            </div>
        </div>
    </header>

    <section class="catalog">
        <div class="catalog-tools">
            <label class="product-search">
                <span aria-hidden="true">⌕</span>
                <input id="product-search" type="search" placeholder="{{ __('pos.search_placeholder') }}">
            </label>
            <div class="filters" aria-label="{{ __('pos.categories') }}">
                <button class="filter-chip is-active" type="button" data-filter="all">{{ __('pos.all_products') }}</button>
                @php
                    $categories = collect($products)->map(fn ($product) => $product['tag'] ?? $product['category'] ?? '')->filter()->unique();
                @endphp
                @foreach ($categories as $category)
                    <button class="filter-chip" type="button" data-filter="{{ strtolower($category) }}">{{ $category }}</button>
                @endforeach
            </div>
        </div>

        <div class="catalog-heading">
            <div><h2>{{ __('pos.product_catalog') }}</h2><span>{{ __('pos.choose_products') }}</span></div>
            <span>{{ trans_choice('pos.items_available', count($products), ['count' => count($products)]) }}</span>
        </div>

        <div class="product-grid" id="product-grid">
            @foreach ($products as $index => $product)
                @php
                    $sku = $product['item'] ?? $product['code'];
                    $category = $product['tag'] ?? $product['category'];
                @endphp
                <article
                    class="product-card"
                    data-product-card
                    data-index="{{ $index }}"
                    data-name="{{ strtolower($product['name']) }}"
                    data-sku="{{ strtolower($sku) }}"
                    data-category="{{ strtolower($category) }}"
                >
                    <div class="product-card__image">
                        @if (!empty($product['image']))
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        @else
                            <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
                                <path d="M22 12 10 20l7 11 6-4v25h18V27l6 4 7-11-12-8c-2 4-5 6-10 6s-8-2-10-6Z" fill="currentColor" opacity=".88"/>
                                <path d="M23 12c1 4 4 6 9 6s8-2 10-6" stroke="var(--pos-panel)" stroke-width="2"/>
                            </svg>
                        @endif
                        <span class="product-tag">{{ $category }}</span>
                    </div>
                    <div class="product-card__body">
                        <h3>{{ $product['name'] }}</h3>
                        <p>{{ __('common.item') }}: {{ $sku }}</p>
                        <p>{{ __('common.variants') }}: {{ $product['variants'] ?? __('common.not_available') }}</p>
                        <div class="product-card__footer">
                            <div>
                                <div class="product-price">IQD {{ number_format($product['price']) }}</div>
                                <div class="product-stock">{{ __('common.stock') }}: {{ $product['stock'] }}</div>
                            </div>
                            <button class="add-product" type="button" data-add-product {{ $product['stock'] < 1 ? 'disabled' : '' }}>+</button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection

@section('summary')
    <aside class="checkout-panel" aria-label="{{ __('checkout.summary') }}">
        <div class="checkout-head">
            <h2>{{ __('checkout.summary') }}</h2>
            <span class="item-count" id="cart-count">{{ __('checkout.item_count', ['count' => 0]) }}</span>
        </div>

        <div class="cart-empty" id="cart-empty"><div><span>🛒</span>{{ __('checkout.empty_cart') }}</div></div>
        <div class="cart-list" id="cart-items" hidden></div>

        <div class="checkout-calculation">
            <div class="calculation-row"><span>{{ __('checkout.subtotal') }}</span><strong id="subtotal">IQD 0</strong></div>
            <div class="calculation-row"><span>{{ __('checkout.discount') }}</span></div>
            <div class="discount-control">
                <select id="discount-type" aria-label="{{ __('checkout.discount_type') }}">
                    <option value="percent">%</option>
                    <option value="fixed">IQD</option>
                </select>
                <input id="discount-value" type="number" min="0" value="0" aria-label="{{ __('checkout.discount') }}">
            </div>
        </div>

        <div class="checkout-total">
            <span>{{ __('checkout.total') }}</span>
            <strong id="total-amount">IQD 0</strong>
        </div>

        <div class="payment-methods">
            <span>{{ __('checkout.payment_method') }}</span>
            <div class="payment-methods__grid">
                <button class="payment-method is-active" type="button" data-payment-method="cash">{{ __('payment.cash') }}</button>
                <button class="payment-method" type="button" data-payment-method="mobile_pay">{{ __('payment.mobile_pay') }}</button>
                <button class="payment-method" type="button" data-payment-method="card">{{ __('payment.card') }}</button>
            </div>
        </div>

        <div class="checkout-actions">
            <button class="checkout-button checkout-button--primary" id="complete-payment" type="button" disabled>{{ __('checkout.complete') }}</button>
            <button class="checkout-button checkout-button--danger" id="clear-cart" type="button">{{ __('common.delete') }}</button>
        </div>
    </aside>
@endsection

@section('overlays')
    <div class="modal-backdrop" id="payment-modal" aria-hidden="true">
        <section class="payment-modal" role="dialog" aria-modal="true" aria-labelledby="payment-title">
            <header class="payment-modal__head">
                <h2 id="payment-title">{{ __('payment.title') }}</h2>
                <button class="payment-modal__close" id="close-payment" type="button" aria-label="{{ __('common.close') }}">×</button>
            </header>
            <div class="payment-modal__body">
                <p class="payment-modal__note">{{ __('payment.note') }}</p>
                <div class="payment-highlight"><span>{{ __('payment.amount_due') }}</span><strong id="modal-total">IQD 0</strong></div>
                <label class="payment-input">
                    <span>{{ __('payment.amount_paid') }}</span>
                    <input id="amount-paid" type="number" min="0" step="1" value="0">
                </label>
                <div class="quick-amounts">
                    @foreach ([50000, 100000, 150000, 200000] as $amount)
                        <button type="button" data-quick-amount="{{ $amount }}">IQD {{ number_format($amount) }}</button>
                    @endforeach
                </div>
                <div class="payment-highlight"><span>{{ __('payment.change_due') }}</span><strong id="change-due">IQD 0</strong></div>
                <p class="payment-error" id="payment-error">{{ __('payment.insufficient') }}</p>
                <div class="payment-modal__actions">
                    <button class="save-print" id="save-print" type="button">{{ __('payment.save_print') }}</button>
                    <button class="save-only" id="save-only" type="button">{{ __('payment.save_only') }}</button>
                </div>
            </div>
        </section>
    </div>
    <div class="toast-container" id="toast-container" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
    (() => {
        const products = @json($products);
        const locale = @json(app()->getLocale() === 'ar' ? 'ar-IQ' : 'en-US');
        const strings = {
            itemCount: @json(__('checkout.item_count')),
            remove: @json(__('common.remove')),
            stockLimit: @json(__('checkout.stock_limit')),
            paymentSaved: @json(__('payment.saved')),
            saveError: @json(__('payment.save_error')),
            insufficient: @json(__('payment.insufficient')),
            receiptTitle: @json(__('receipt.title')),
            cashier: @json(__('common.cashier')),
            total: @json(__('checkout.total')),
            paid: @json(__('payment.amount_paid')),
            change: @json(__('payment.change_due')),
            method: @json(__('checkout.payment_method')),
            thankYou: @json(__('receipt.thank_you')),
        };
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const cart = new Map();
        let activeFilter = 'all';
        let paymentMethod = 'cash';

        const elements = {
            search: document.getElementById('product-search'),
            cards: [...document.querySelectorAll('[data-product-card]')],
            filters: [...document.querySelectorAll('[data-filter]')],
            addButtons: [...document.querySelectorAll('[data-add-product]')],
            cartEmpty: document.getElementById('cart-empty'),
            cartItems: document.getElementById('cart-items'),
            cartCount: document.getElementById('cart-count'),
            subtotal: document.getElementById('subtotal'),
            total: document.getElementById('total-amount'),
            discountType: document.getElementById('discount-type'),
            discountValue: document.getElementById('discount-value'),
            methods: [...document.querySelectorAll('[data-payment-method]')],
            complete: document.getElementById('complete-payment'),
            clear: document.getElementById('clear-cart'),
            modal: document.getElementById('payment-modal'),
            closeModal: document.getElementById('close-payment'),
            modalTotal: document.getElementById('modal-total'),
            paid: document.getElementById('amount-paid'),
            change: document.getElementById('change-due'),
            error: document.getElementById('payment-error'),
            savePrint: document.getElementById('save-print'),
            saveOnly: document.getElementById('save-only'),
            toast: document.getElementById('toast-container'),
            clock: document.getElementById('live-clock'),
        };

        const money = (value) => `IQD ${Number(value || 0).toLocaleString(locale)}`;
        const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (char) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
        }[char]));

        const totals = () => {
            const items = [...cart.values()];
            const subtotal = items.reduce((sum, item) => sum + item.price * item.qty, 0);
            const rawDiscount = Math.max(0, Number(elements.discountValue.value || 0));
            const discount = elements.discountType.value === 'percent'
                ? Math.min(subtotal, subtotal * Math.min(rawDiscount, 100) / 100)
                : Math.min(subtotal, rawDiscount);
            return {
                items,
                subtotal,
                discount,
                total: Math.max(0, subtotal - discount),
                count: items.reduce((sum, item) => sum + item.qty, 0),
            };
        };

        const showToast = (message, detail = '', isError = false) => {
            const toast = document.createElement('div');
            toast.className = `toast${isError ? ' is-error' : ''}`;
            toast.innerHTML = `${escapeHtml(message)}${detail ? `<small>${escapeHtml(detail)}</small>` : ''}`;
            elements.toast.appendChild(toast);
            setTimeout(() => toast.remove(), 3500);
        };

        const updateChange = () => {
            const { total } = totals();
            const paid = Number(elements.paid.value || 0);
            elements.change.textContent = money(Math.max(0, paid - total));
            elements.error.classList.toggle('is-visible', paid < total);
        };

        const renderCart = () => {
            const state = totals();
            elements.cartCount.textContent = strings.itemCount.replace(':count', state.count);
            elements.subtotal.textContent = money(state.subtotal);
            elements.total.textContent = money(state.total);
            elements.modalTotal.textContent = money(state.total);
            elements.complete.disabled = state.items.length === 0;
            elements.cartEmpty.hidden = state.items.length > 0;
            elements.cartItems.hidden = state.items.length === 0;
            elements.cartItems.innerHTML = state.items.map((item) => `
                <article class="cart-item" data-cart-key="${escapeHtml(item.sku)}">
                    <div>
                        <h3>${escapeHtml(item.name)}</h3>
                        <p>${escapeHtml(item.sku)} · ${escapeHtml(item.category)}</p>
                        <p class="cart-item__total">${item.qty} × ${money(item.price)} = ${money(item.qty * item.price)}</p>
                        <button class="cart-remove" type="button" data-remove>${escapeHtml(strings.remove)}</button>
                    </div>
                    <div class="qty-control">
                        <button type="button" data-decrease>−</button>
                        <span>${item.qty}</span>
                        <button type="button" data-increase>+</button>
                    </div>
                </article>
            `).join('');
            updateChange();
        };

        const addProduct = (product) => {
            const sku = product.item || product.code;
            const current = cart.get(sku);
            const stock = Number(product.stock || 0);
            if (current && current.qty >= stock) {
                showToast(strings.stockLimit, product.name, true);
                return;
            }
            cart.set(sku, {
                name: product.name,
                sku,
                category: product.tag || product.category,
                price: Number(product.price),
                stock,
                qty: current ? current.qty + 1 : 1,
            });
            renderCart();
        };

        const filterProducts = () => {
            const query = elements.search.value.trim().toLowerCase();
            elements.cards.forEach((card) => {
                const matchesFilter = activeFilter === 'all' || card.dataset.category === activeFilter;
                const matchesSearch = !query || [card.dataset.name, card.dataset.sku, card.dataset.category].some((value) => value.includes(query));
                card.hidden = !(matchesFilter && matchesSearch);
            });
        };

        const openModal = () => {
            const { total } = totals();
            if (!cart.size) return;
            elements.paid.value = Math.ceil(total);
            elements.modal.classList.add('is-open');
            elements.modal.setAttribute('aria-hidden', 'false');
            updateChange();
            elements.paid.focus();
            elements.paid.select();
        };

        const closeModal = () => {
            elements.modal.classList.remove('is-open');
            elements.modal.setAttribute('aria-hidden', 'true');
            elements.error.classList.remove('is-visible');
        };

        const printReceipt = (printWindow, transaction, state, paid) => {
            if (!printWindow) return;
            const items = state.items.map((item) => `
                <tr><td>${escapeHtml(item.name)} × ${item.qty}</td><td>${money(item.price * item.qty)}</td></tr>
            `).join('');
            printWindow.document.write(`<!doctype html><html dir="${document.documentElement.dir}"><head><title>${escapeHtml(strings.receiptTitle)}</title>
                <style>body{font:14px Arial,sans-serif;max-width:360px;margin:24px auto;color:#111}h1{text-align:center;font-size:20px}table{width:100%;border-collapse:collapse}td{padding:6px 0;border-bottom:1px dashed #bbb}td:last-child{text-align:end}.totals{margin-top:16px;line-height:1.8}.thanks{text-align:center;margin-top:24px}</style>
                </head><body><h1>${escapeHtml(strings.receiptTitle)}</h1><p>#${escapeHtml(transaction.id)}</p><p>${escapeHtml(strings.cashier)}: ${escapeHtml(transaction.cashier_name)}</p><table>${items}</table>
                <div class="totals"><strong>${escapeHtml(strings.total)}: ${money(state.total)}</strong><br>${escapeHtml(strings.paid)}: ${money(paid)}<br>${escapeHtml(strings.change)}: ${money(Math.max(0, paid - state.total))}<br>${escapeHtml(strings.method)}: ${escapeHtml(paymentMethod)}</div>
                <p class="thanks">${escapeHtml(strings.thankYou)}</p><script>window.onload=()=>{window.print();window.close()}<\/script></body></html>`);
            printWindow.document.close();
        };

        const finalizePayment = async (shouldPrint) => {
            const state = totals();
            const paid = Number(elements.paid.value || 0);
            if (!state.items.length || paid < state.total) {
                elements.error.classList.add('is-visible');
                showToast(strings.insufficient, '', true);
                return;
            }

            const printWindow = shouldPrint ? window.open('', '_blank', 'width=420,height=700') : null;
            elements.savePrint.disabled = true;
            elements.saveOnly.disabled = true;

            try {
                const response = await fetch(@json(route('sales.save')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        total_items: state.count,
                        subtotal: state.subtotal,
                        discount: state.discount,
                        total_payment: state.total,
                        amount_paid: paid,
                        change_due: Math.max(0, paid - state.total),
                        payment_method: paymentMethod,
                        items: state.items,
                    }),
                });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.message || strings.saveError);

                if (shouldPrint) printReceipt(printWindow, data.transaction, state, paid);
                showToast(strings.paymentSaved, money(state.total));
                cart.clear();
                elements.discountValue.value = 0;
                elements.discountType.value = 'percent';
                elements.paid.value = 0;
                closeModal();
                renderCart();
            } catch (error) {
                if (printWindow) printWindow.close();
                showToast(strings.saveError, error.message, true);
            } finally {
                elements.savePrint.disabled = false;
                elements.saveOnly.disabled = false;
            }
        };

        elements.addButtons.forEach((button) => button.addEventListener('click', () => {
            const card = button.closest('[data-product-card]');
            addProduct(products[Number(card.dataset.index)]);
        }));
        elements.filters.forEach((button) => button.addEventListener('click', () => {
            activeFilter = button.dataset.filter;
            elements.filters.forEach((item) => item.classList.toggle('is-active', item === button));
            filterProducts();
        }));
        elements.search.addEventListener('input', filterProducts);
        elements.discountType.addEventListener('change', renderCart);
        elements.discountValue.addEventListener('input', renderCart);
        elements.methods.forEach((button) => button.addEventListener('click', () => {
            paymentMethod = button.dataset.paymentMethod;
            elements.methods.forEach((item) => item.classList.toggle('is-active', item === button));
        }));
        elements.complete.addEventListener('click', openModal);
        elements.closeModal.addEventListener('click', closeModal);
        elements.modal.addEventListener('click', (event) => {
            if (event.target === elements.modal) closeModal();
        });
        elements.paid.addEventListener('input', updateChange);
        document.querySelectorAll('[data-quick-amount]').forEach((button) => button.addEventListener('click', () => {
            elements.paid.value = button.dataset.quickAmount;
            updateChange();
        }));
        elements.savePrint.addEventListener('click', () => finalizePayment(true));
        elements.saveOnly.addEventListener('click', () => finalizePayment(false));
        elements.clear.addEventListener('click', () => {
            cart.clear();
            elements.discountValue.value = 0;
            renderCart();
        });
        elements.cartItems.addEventListener('click', (event) => {
            const row = event.target.closest('[data-cart-key]');
            if (!row) return;
            const item = cart.get(row.dataset.cartKey);
            if (event.target.matches('[data-increase]')) {
                if (item.qty >= item.stock) {
                    showToast(strings.stockLimit, item.name, true);
                    return;
                }
                cart.set(item.sku, { ...item, qty: item.qty + 1 });
            } else if (event.target.matches('[data-decrease]')) {
                item.qty > 1 ? cart.set(item.sku, { ...item, qty: item.qty - 1 }) : cart.delete(item.sku);
            } else if (event.target.matches('[data-remove]')) {
                cart.delete(item.sku);
            }
            renderCart();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeModal();
        });

        const updateClock = () => {
            elements.clock.textContent = new Intl.DateTimeFormat(locale, {
                timeZone: 'Asia/Baghdad', hour: '2-digit', minute: '2-digit', second: '2-digit',
            }).format(new Date());
        };
        updateClock();
        setInterval(updateClock, 1000);
        renderCart();
    })();
</script>
@endpush
