<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cashier ERP</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --panel: #ffffff;
            --soft: #edf2fb;
            --line: #dbe3ef;
            --text: #182235;
            --muted: #71809a;
            --blue: #315fd1;
            --blue-deep: #2448a6;
            --blue-soft: #eef4ff;
            --green: #1f9d66;
            --amber: #d59b18;
            --red: #cb4d4d;
            --shadow: 0 18px 44px rgba(18, 36, 74, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(64, 107, 217, 0.06), transparent 30%),
                linear-gradient(180deg, #f8faff 0%, var(--bg) 100%);
            color: var(--text);
        }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr) 318px;
        }

        .sidebar,
        .summary,
        .workspace {
            min-height: 100vh;
        }

        .sidebar {
            background: var(--panel);
            border-right: 1px solid var(--line);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px 16px;
            border-bottom: 1px solid var(--soft);
        }

        .brand-mark {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--blue-deep));
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 14px;
            font-weight: 700;
        }

        .brand-copy strong,
        .brand-copy span {
            display: block;
            line-height: 1.15;
        }

        .brand-copy strong {
            font-size: 14px;
        }

        .brand-copy span {
            margin-top: 4px;
            font-size: 11px;
            color: var(--muted);
        }

        .nav {
            display: grid;
            gap: 8px;
            padding: 16px 2px 0;
        }

        .nav a,
        .nav button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 0;
            background: transparent;
            color: var(--text);
            text-decoration: none;
            font-size: 13px;
            border-radius: 10px;
            padding: 11px 14px;
            cursor: pointer;
            text-align: left;
        }

        .nav a.active {
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: 700;
        }

        .nav .icon {
            width: 18px;
            text-align: center;
            opacity: 0.9;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--soft);
            padding-top: 14px;
        }

        .logout {
            color: var(--red);
        }

        .workspace {
            padding: 18px 20px 20px;
        }

        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .title h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: -0.02em;
        }

        .title p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .head-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .meta-card,
        .user-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow);
            padding: 10px 12px;
            min-height: 48px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .meta-card small,
        .user-card small {
            display: block;
            color: var(--muted);
            font-size: 11px;
        }

        .meta-card strong,
        .user-card strong {
            display: block;
            font-size: 12px;
        }

        .meta-badge {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: var(--blue-soft);
            color: var(--blue);
            display: grid;
            place-items: center;
            font-weight: 700;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5d0d8, #f8e3a8);
        }

        .catalog-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 16px;
        }

        .catalog-tools {
            display: grid;
            gap: 14px;
            margin-bottom: 16px;
        }

        .search {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 44px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--muted);
        }

        .search input {
            width: 100%;
            border: 0;
            outline: 0;
            font-size: 13px;
            color: var(--text);
        }

        .filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chip {
            border: 1px solid var(--line);
            background: #fff;
            color: var(--muted);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
        }

        .chip.active {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
            font-weight: 700;
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .section-head h2 {
            margin: 0;
            font-size: 16px;
        }

        .section-head span {
            color: var(--muted);
            font-size: 12px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .product {
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            min-height: 228px;
            display: flex;
            flex-direction: column;
        }

        .product-image {
            position: relative;
            height: 120px;
            background:
                linear-gradient(135deg, rgba(49, 95, 209, 0.15), rgba(49, 95, 209, 0.03)),
                linear-gradient(135deg, #cfd7e7, #eef3fa);
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tag {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(49, 95, 209, 0.82);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 999px;
            padding: 4px 8px;
        }

        .product-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }

        .product-body h3 {
            margin: 0;
            font-size: 14px;
        }

        .product-body p {
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.35;
        }

        .product-footer {
            margin-top: auto;
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 10px;
            padding-top: 8px;
        }

        .price {
            color: var(--blue);
            font-size: 14px;
            font-weight: 700;
        }

        .stock {
            color: var(--muted);
            font-size: 10px;
        }

        .add {
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 8px;
            background: var(--blue);
            color: #fff;
            font-size: 18px;
        }

        .summary {
            background: var(--panel);
            border-left: 1px solid var(--line);
            padding: 18px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: auto;
        }

        .summary-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .summary-head h2 {
            margin: 0;
            font-size: 16px;
        }

        .pill {
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 11px;
            font-weight: 700;
        }

        .cart-box {
            min-height: 110px;
            border: 1px dashed var(--line);
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: #fafcff;
            color: var(--muted);
            font-size: 13px;
        }

        .order-list {
            display: grid;
            gap: 10px;
        }

        .order-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
        }

        .order-item strong {
            display: block;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .order-item small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.35;
        }

        .order-item .actions {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--blue);
            font-weight: 700;
            font-size: 12px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--line);
            font-size: 13px;
        }

        .summary-row span {
            color: var(--muted);
        }

        .summary-row strong {
            color: var(--text);
        }

        .discount,
        .payment-methods,
        .total {
            display: grid;
            gap: 8px;
        }

        .discount label,
        .payment-methods label,
        .total label {
            color: var(--muted);
            font-size: 12px;
        }

        .discount-controls {
            display: grid;
            grid-template-columns: 68px 1fr;
            gap: 8px;
        }

        .discount-controls select,
        .discount-controls input {
            min-height: 38px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0 10px;
            outline: 0;
            font-size: 12px;
        }

        .total {
            border-top: 1px solid var(--soft);
            padding-top: 4px;
        }

        .total strong {
            font-size: 24px;
            color: var(--blue);
        }

        .methods {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .method {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 10px;
            padding: 8px 10px;
            color: var(--muted);
            font-size: 12px;
        }

        .method.active {
            background: var(--blue-soft);
            color: var(--blue);
            border-color: var(--blue);
            font-weight: 700;
        }

        .action-btn {
            min-height: 42px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--muted);
            font-weight: 700;
        }

        .action-btn.primary {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
        }

        .action-btn.danger {
            color: var(--red);
        }

        .action-btn:disabled,
        .add:disabled,
        .method:disabled,
        .chip:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .empty-state {
            min-height: 110px;
            border: 1px dashed var(--line);
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: #fafcff;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            padding: 18px;
        }

        .cart-items {
            display: grid;
            gap: 10px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
        }

        .cart-item h4 {
            margin: 0 0 3px;
            font-size: 13px;
        }

        .cart-item small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.35;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--blue);
            font-weight: 700;
            font-size: 12px;
        }

        .qty-btn {
            width: 26px;
            height: 26px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            color: var(--text);
            font-weight: 700;
            line-height: 1;
        }

        .remove-btn {
            border: 0;
            background: transparent;
            color: var(--red);
            font-size: 13px;
            padding: 0;
            margin-top: 4px;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(18, 24, 38, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 50;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal {
            width: min(560px, 100%);
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 30px 80px rgba(18, 24, 38, 0.25);
            border: 1px solid var(--line);
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px 12px;
            border-bottom: 1px solid var(--soft);
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .modal-close {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 0;
            background: var(--soft);
            color: var(--text);
            font-size: 18px;
            font-weight: 700;
        }

        .modal-body {
            padding: 18px 20px 20px;
            display: grid;
            gap: 14px;
        }

        .modal-note {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .amount-row,
        .change-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: var(--soft);
            border-radius: 12px;
            padding: 14px 16px;
        }

        .amount-row label,
        .change-row label {
            display: block;
            color: var(--text);
            font-weight: 700;
            margin-bottom: 2px;
        }

        .amount-row small,
        .change-row small {
            display: block;
            color: var(--muted);
            font-size: 11px;
        }

        .amount-row strong,
        .change-row strong {
            color: var(--blue);
            font-size: 20px;
            font-weight: 700;
        }

        .payment-input {
            display: grid;
            gap: 8px;
        }

        .payment-input label {
            font-size: 13px;
            color: var(--text);
            font-weight: 700;
        }

        .payment-input input {
            min-height: 42px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0 14px;
            font-size: 14px;
            outline: 0;
        }

        .quick-amounts {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }

        .quick-amounts button {
            min-height: 36px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--muted);
            font-size: 12px;
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .modal-actions button {
            min-height: 42px;
            border-radius: 10px;
            border: 1px solid var(--line);
            font-weight: 700;
        }

        .modal-actions .save-print {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
        }

        .modal-actions .save-only {
            background: #fff;
            color: var(--blue);
        }

        @media (max-width: 1280px) {
            .app {
                grid-template-columns: 220px minmax(0, 1fr);
            }

            .summary {
                display: none;
            }

            .top-grid,
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .app {
                grid-template-columns: 1fr;
            }

            .sidebar {
                min-height: auto;
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }

            .workspace {
                min-height: auto;
            }

            .page-head {
                flex-direction: column;
            }
        }

        @media (max-width: 640px) {
            .workspace {
                padding: 14px;
            }

            .top-grid,
            .product-grid {
                grid-template-columns: 1fr;
            }

            .head-meta {
                width: 100%;
            }
        }

        /* ── Toast Notification ─────────────────── */
        .toast-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 16px 36px rgba(18,36,74,0.1);
            min-width: 260px;
            animation: slideUp 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .toast.success { border-left: 4px solid #1f9d66; }
        .toast.error   { border-left: 4px solid #cb4d4d; }
        .toast-icon    { font-size: 20px; flex-shrink: 0; }
        .toast p       { margin: 0; color: #182235; }
        .toast span    { display: block; font-size: 11px; color: #71809a; font-weight: 400; margin-top: 1px; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    </style>
</head>
<body>


    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">P</div>
                <div class="brand-copy">
                    <strong>Pos System</strong>
                    <span>System Cashier</span>
                </div>
            </div>

            <nav class="nav" aria-label="Sidebar navigation">
                <a class="active" href="{{ route('dashboard') }}"><span class="icon">▣</span><span>POS / Cashier</span></a>
                <a href="{{ route('inventory.index') }}"><span class="icon">▥</span><span>Inventory Management</span></a>
                <a href="{{ route('sales.index') }}"><span class="icon">◷</span><span>Sales Menu</span></a>
                <a href="{{ route('dashboard') }}"><span class="icon">▤</span><span>Dashboard</span></a>
                <a href="{{ route('dashboard') }}"><span class="icon">⚙</span><span>Setting</span></a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">
                        <span class="icon">⇦</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="workspace" id="overview">
            <div class="page-head">
                <div class="title">
                    <h1>Cashier</h1>
                    <p>Store operations and product selection for apparel sales.</p>
                </div>

                <div class="head-meta">
                    <div class="meta-card">
                        <div class="meta-badge">⏱</div>
                        <div>
                            <small>Time</small>
                            <strong>15:07:14 WIB</strong>
                        </div>
                    </div>
                    <div class="user-card">
                        <div class="avatar" aria-hidden="true"></div>
                        <div>
                            <small>Operator</small>
                            <strong>{{ auth()->user()->name ?? 'Cashier' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <section class="catalog-card" id="catalog">
                <div class="catalog-tools">
                    <label class="search" aria-label="Search products">
                        <span>⌕</span>
                        <input id="product-search" type="search" placeholder="Search by product name, SKU, or category">
                    </label>

                    <div class="filters" aria-label="Product categories">
                        <button class="chip active" type="button" data-filter="all">All Products</button>
                        @php
                            $categories = [];
                            foreach ($products as $product) {
                                $cat = $product['tag'] ?? $product['category'] ?? '';
                                if ($cat && ! in_array($cat, $categories, true)) {
                                    $categories[] = $cat;
                                }
                            }
                        @endphp
                        @foreach ($categories as $category)
                            <button class="chip" type="button" data-filter="{{ $category }}">{{ $category }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="section-head">
                    <div>
                        <h2>Product Catalog</h2>
                        <span>Choose clothing items for the current sale.</span>
                    </div>
                    <span>{{ count($products) }} items</span>
                </div>

                <div class="product-grid">
                    @foreach ($products as $index => $product)
                        <article class="product" data-product-card data-filter-value="{{ $product['tag'] ?? $product['category'] }}" data-name="{{ strtolower($product['name']) }}" data-sku="{{ strtolower($product['item'] ?? $product['code']) }}" data-category="{{ strtolower($product['tag'] ?? $product['category']) }}" data-index="{{ $index }}">
                            <div class="product-image">
                                @if (!empty($product['image']))
                                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                                @endif
                                <span class="tag">{{ $product['tag'] ?? $product['category'] }}</span>
                            </div>
                            <div class="product-body">
                                <h3>{{ $product['name'] }}</h3>
                                <p>item: {{ $product['item'] ?? $product['code'] }}</p>
                                <p>size: {{ $product['size'] ?? 'N/A' }}</p>
                                <p>color: {{ $product['color'] ?? 'N/A' }}</p>

                                <div class="product-footer">
                                    <div>
                                        <div class="price">IQD {{ number_format($product['price'], 0, '.', ',') }}</div>
                                        <div class="stock">Stock: {{ $product['stock'] }}</div>
                                    </div>
                                    <button class="add" type="button" data-add-product>+</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>

        <aside class="summary" id="summary">
            <div class="summary-head">
                <h2>Checkout Summary</h2>
                <span class="pill" id="cart-count">0 Item</span>
            </div>

            <div id="cart-empty" class="empty-state">Cart is empty. Add items from the catalog to build a sale.</div>
            <div id="cart-items" class="cart-items"></div>

            <div class="summary-row">
                <span>Sub total</span>
                <strong id="sub-total">IQD 0</strong>
            </div>

            <div class="discount">
                <label for="discount-value">Discount</label>
                <div class="discount-controls">
                    <select id="discount-type">
                        <option>%</option>
                        <option>IQD</option>
                    </select>
                    <input id="discount-value" type="number" value="0" min="0">
                </div>
            </div>

            <div class="total">
                <label>Total Amount</label>
                <strong id="total-amount">IQD 0</strong>
            </div>

            <div class="payment-methods">
                <label>Payment Method</label>
                <div class="methods">
                    <button class="method active" type="button" data-payment-method="Cash">Cash</button>
                    <button class="method" type="button" data-payment-method="Mobile Pay">Mobile Pay</button>
                    <button class="method" type="button" data-payment-method="Card">Card</button>
                </div>
            </div>

            <button id="complete-payment" class="action-btn primary" type="button" disabled>Complete Payment</button>
            <button id="delete-cart" class="action-btn danger" type="button">Delete</button>
        </aside>
    </div>

    <div id="payment-modal" class="modal-backdrop" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="payment-modal-title">
            <div class="modal-header">
                <h3 id="payment-modal-title">Payment</h3>
                <button id="close-payment-modal" class="modal-close" type="button" aria-label="Close payment dialog">×</button>
            </div>
            <div class="modal-body">
                <p class="modal-note">Enter the amount received from the customer.</p>

                <div class="amount-row">
                    <div>
                        <label>Total amount to be paid</label>
                        <small>Current order total</small>
                    </div>
                    <strong id="modal-total">IQD 0</strong>
                </div>

                <div class="payment-input">
                    <label for="amount-paid">Amount paid</label>
                    <input id="amount-paid" type="number" min="0" step="1" value="0">
                </div>

                <div class="quick-amounts" aria-label="Quick amount buttons">
                    <button type="button" data-quick-amount="50000">IQD 50.000</button>
                    <button type="button" data-quick-amount="100000">IQD 100.000</button>
                    <button type="button" data-quick-amount="150000">IQD 150.000</button>
                    <button type="button" data-quick-amount="200000">IQD 200.000</button>
                </div>

                <div class="change-row">
                    <div>
                        <label>Change due</label>
                        <small>Amount to return</small>
                    </div>
                    <strong id="change-due">IQD 0</strong>
                </div>

                <div class="modal-actions">
                    <button id="save-print-receipt" class="save-print" type="button">Save & print receipt</button>
                    <button id="save-only-receipt" class="save-only" type="button">Save without printing receipt</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        (() => {
            'use strict';
            
            /* ─── Utility: show toast notification ───────── */
            function showToast(type, title, subtitle = '') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                const icon = type === 'success' ? '✅' : '❌';
                toast.innerHTML = `
                    <span class="toast-icon">${icon}</span>
                    <p>${title}${subtitle ? `<span>${subtitle}</span>` : ''}</p>
                `;
                container.appendChild(toast);
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.4s, transform 0.4s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px)';
                    setTimeout(() => toast.remove(), 450);
                }, 3500);
            }

            const products = @json($products);
            const currency = (value) => 'IQD ' + Number(value || 0).toLocaleString('en-US').replace(/,/g, '.');

            const cartItemsElement = document.getElementById('cart-items');
            const cartEmptyElement = document.getElementById('cart-empty');
            const cartCountElement = document.getElementById('cart-count');
            const subtotalElement = document.getElementById('sub-total');
            const totalAmountElement = document.getElementById('total-amount');
            const discountTypeElement = document.getElementById('discount-type');
            const discountValueElement = document.getElementById('discount-value');
            const completePaymentButton = document.getElementById('complete-payment');
            const deleteCartButton = document.getElementById('delete-cart');
            const paymentModal = document.getElementById('payment-modal');
            const closePaymentModalButton = document.getElementById('close-payment-modal');
            const modalTotalElement = document.getElementById('modal-total');
            const amountPaidElement = document.getElementById('amount-paid');
            const changeDueElement = document.getElementById('change-due');
            const savePrintReceiptButton = document.getElementById('save-print-receipt');
            const saveOnlyReceiptButton = document.getElementById('save-only-receipt');
            const quickAmountButtons = Array.from(document.querySelectorAll('[data-quick-amount]'));
            const searchInput = document.getElementById('product-search');
            const productCards = Array.from(document.querySelectorAll('[data-product-card]'));
            const filterButtons = Array.from(document.querySelectorAll('[data-filter]'));
            const paymentButtons = Array.from(document.querySelectorAll('[data-payment-method]'));
            const addButtons = Array.from(document.querySelectorAll('[data-add-product]'));

            const cart = new Map();
            let activeFilter = 'all';
            let activePaymentMethod = 'Cash';

            const normalizeItem = (item) => ({
                name: item.name,
                sku: item.sku,
                category: item.category,
                size: item.size,
                color: item.color,
                price: Number(item.price),
                qty: Number(item.qty || 1),
            });



            const getDiscountValue = (subtotal) => {
                const rawValue = Number(discountValueElement.value || 0);

                if (discountTypeElement.value === '%') {
                    return Math.min(subtotal, subtotal * (rawValue / 100));
                }

                return Math.min(subtotal, rawValue);
            };

            const renderCart = () => {
                const items = Array.from(cart.values());
                const subtotal = items.reduce((sum, item) => sum + item.price * item.qty, 0);
                const discountValue = getDiscountValue(subtotal);
                const total = Math.max(0, subtotal - discountValue);
                const totalItems = items.reduce((sum, item) => sum + item.qty, 0);

                cartCountElement.textContent = `${totalItems} Item`;
                subtotalElement.textContent = currency(subtotal);
                totalAmountElement.textContent = currency(total);
                modalTotalElement.textContent = currency(total);
                updateChangeDue();
                completePaymentButton.disabled = items.length === 0;

                if (!items.length) {
                    cartEmptyElement.style.display = 'grid';
                    cartItemsElement.innerHTML = '';
                    return;
                }

                cartEmptyElement.style.display = 'none';
                cartItemsElement.innerHTML = items.map((item) => `
                    <div class="cart-item" data-cart-key="${item.sku}">
                        <div>
                            <h4>${item.name}</h4>
                            <small>${item.sku} | ${item.category}</small>
                            <small>${item.qty} x ${currency(item.price)} = ${currency(item.price * item.qty)}</small>
                            <button class="remove-btn" type="button" data-remove-item>Remove</button>
                        </div>
                        <div class="qty-controls">
                            <button class="qty-btn" type="button" data-decrease>-</button>
                            <span>${item.qty}</span>
                            <button class="qty-btn" type="button" data-increase>+</button>
                        </div>
                    </div>
                `).join('');
            };

            const addProduct = (product) => {
                const key = product.item || product.code;

                if (cart.has(key)) {
                    const current = cart.get(key);
                    cart.set(key, { ...current, qty: current.qty + 1 });
                } else {
                    cart.set(key, {
                        name: product.name,
                        sku: product.item || product.code,
                        category: product.tag || product.category,
                        size: product.size || '-',
                        color: product.color || '-',
                        price: Number(product.price),
                        qty: 1,
                    });
                }

                renderCart();
            };

            const updateProductVisibility = () => {
                const query = searchInput.value.trim().toLowerCase();

                productCards.forEach((card) => {
                    const matchesFilter = activeFilter === 'all' || card.dataset.category === activeFilter.toLowerCase();
                    const matchesSearch = !query || [card.dataset.name, card.dataset.sku, card.dataset.category].some((value) => value.includes(query));
                    card.style.display = matchesFilter && matchesSearch ? '' : 'none';
                });
            };

            const setActivePaymentMethod = (method) => {
                activePaymentMethod = method;
                paymentButtons.forEach((button) => {
                    button.classList.toggle('active', button.dataset.paymentMethod === method);
                });
            };

            const setActiveFilter = (filter) => {
                activeFilter = filter;
                filterButtons.forEach((button) => {
                    button.classList.toggle('active', button.dataset.filter === filter);
                });
                updateProductVisibility();
            };

            const openPaymentModal = () => {
                if (!cart.size) {
                    return;
                }

                paymentModal.classList.add('open');
                paymentModal.setAttribute('aria-hidden', 'false');
                amountPaidElement.value = Number(totalAmountElement.textContent.replace(/[^0-9]/g, '') || 0);
                updateChangeDue();
                amountPaidElement.focus();
                amountPaidElement.select();
            };

            const closePaymentModal = () => {
                paymentModal.classList.remove('open');
                paymentModal.setAttribute('aria-hidden', 'true');
            };

            const currentTotalValue = () => Number(totalAmountElement.textContent.replace(/[^0-9]/g, '') || 0);

            function updateChangeDue() {
                const paid = Number(amountPaidElement?.value || 0);
                const total = currentTotalValue();
                const change = Math.max(0, paid - total);
                changeDueElement.textContent = currency(change);
            }

            const finalizePayment = async (message) => {
                if (!cart.size) {
                    return;
                }

                const items = Array.from(cart.values());
                const subtotal = items.reduce((sum, item) => sum + item.price * item.qty, 0);
                const discountValue = getDiscountValue(subtotal);
                const total = Math.max(0, subtotal - discountValue);
                const totalItems = items.reduce((sum, item) => sum + item.qty, 0);

                try {
                    const response = await fetch('{{ route('sales.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            total_items: totalItems,
                            total_payment: total,
                            payment_method: activePaymentMethod.toLowerCase(),
                            items: items
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        showToast('success', message || 'Payment completed', `Total: ${currency(total)}`);
                        
                        // Clear cart
                        cart.clear();
                        discountValueElement.value = 0;
                        discountTypeElement.value = '%';
                        amountPaidElement.value = 0;
                        setActivePaymentMethod('Cash');
                        renderCart();
                        closePaymentModal();
                    } else {
                        showToast('error', 'Failed to save transaction', data.message || 'Unknown error');
                    }
                } catch (err) {
                    console.error(err);
                    showToast('error', 'Error saving transaction', err.message);
                }
            };


            renderCart();
            setActivePaymentMethod('Cash');
            updateProductVisibility();

            addButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const card = button.closest('[data-product-card]');
                    const product = products[Number(card.dataset.index)];
                    addProduct(product);
                });
            });

            filterButtons.forEach((button) => {
                button.addEventListener('click', () => setActiveFilter(button.dataset.filter));
            });

            searchInput.addEventListener('input', updateProductVisibility);
            discountTypeElement.addEventListener('change', renderCart);
            discountValueElement.addEventListener('input', renderCart);

            paymentButtons.forEach((button) => {
                button.addEventListener('click', () => setActivePaymentMethod(button.dataset.paymentMethod));
            });

            completePaymentButton.addEventListener('click', openPaymentModal);

            closePaymentModalButton.addEventListener('click', closePaymentModal);

            paymentModal.addEventListener('click', (event) => {
                if (event.target === paymentModal) {
                    closePaymentModal();
                }
            });

            amountPaidElement.addEventListener('input', updateChangeDue);

            quickAmountButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    amountPaidElement.value = button.dataset.quickAmount;
                    updateChangeDue();
                });
            });

            savePrintReceiptButton.addEventListener('click', () => {
                finalizePayment('Payment saved and receipt printed.');
            });

            saveOnlyReceiptButton.addEventListener('click', () => {
                finalizePayment('Payment saved without printing receipt.');
            });

            cartItemsElement.addEventListener('click', (event) => {
                const itemElement = event.target.closest('[data-cart-key]');

                if (!itemElement) {
                    return;
                }

                const key = itemElement.dataset.cartKey;
                const item = cart.get(key);

                if (event.target.matches('[data-increase]')) {
                    cart.set(key, { ...item, qty: item.qty + 1 });
                }

                if (event.target.matches('[data-decrease]')) {
                    if (item.qty > 1) {
                        cart.set(key, { ...item, qty: item.qty - 1 });
                    } else {
                        cart.delete(key);
                    }
                }

                if (event.target.matches('[data-remove-item]')) {
                    cart.delete(key);
                }

                renderCart();
            });

            deleteCartButton.addEventListener('click', () => {
                cart.clear();
                discountValueElement.value = 0;
                discountTypeElement.value = '%';
                amountPaidElement.value = 0;
                renderCart();
            });
        })();
    </script>
</body>
</html>