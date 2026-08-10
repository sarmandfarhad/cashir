<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
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
            --green-soft: #e8f7f0;
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
            grid-template-columns: 240px minmax(0, 1fr);
        }

        .sidebar {
            background: var(--panel);
            border-right: 1px solid var(--line);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .nav a {
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
            transition: all 0.2s ease;
        }

        .nav a:hover {
            background: var(--soft);
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
            border: 0;
            background: transparent;
            color: var(--red);
            font-size: 13px;
            padding: 11px 14px;
            cursor: pointer;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .workspace {
            padding: 24px;
        }

        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .title h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: -0.02em;
            font-weight: 700;
        }

        .title p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .head-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .bell-btn {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            cursor: pointer;
            box-shadow: var(--shadow);
            position: relative;
            font-size: 16px;
        }

        .bell-btn::after {
            content: '';
            position: absolute;
            top: 12px;
            right: 12px;
            width: 7px;
            height: 7px;
            background: var(--blue);
            border-radius: 50%;
            border: 1px solid #fff;
        }

        .meta-card,
        .user-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow);
            padding: 8px 16px;
            min-height: 44px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .meta-card small,
        .user-card small {
            display: block;
            color: var(--muted);
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .meta-card strong,
        .user-card strong {
            display: block;
            font-size: 12px;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5d0d8, #f8e3a8);
            display: grid;
            place-items: center;
            font-size: 14px;
        }

        /* Stats Cards Layout */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .stats-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow);
        }

        .stats-card.green {
            background: #f1faf6;
            border-color: #d1f2e1;
        }

        .stats-card.blue {
            background: #f2f7ff;
            border-color: #d2e4ff;
        }

        .stats-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .stats-card.green .stats-icon-wrapper {
            background: #dff6eb;
            color: var(--green);
        }

        .stats-card.blue .stats-icon-wrapper {
            background: #e1eeff;
            color: var(--blue);
        }

        .stats-info {
            display: flex;
            flex-direction: column;
        }

        .stats-info label {
            font-size: 13px;
            color: var(--muted);
        }

        .stats-card.green .stats-info label {
            color: #1a7f52;
            font-weight: 600;
        }

        .stats-card.blue .stats-info label {
            color: #2457c5;
            font-weight: 600;
        }

        .stats-info strong {
            font-size: 22px;
            font-weight: 700;
            margin-top: 4px;
        }

        .stats-card.green .stats-info strong {
            color: #115939;
        }

        .stats-card.blue .stats-info strong {
            color: var(--blue);
        }

        /* Toolbar styles */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }



        .search-wrap {
            flex: 1;
            max-width: 440px;
            position: relative;
        }

        .search-wrap span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 16px;
        }

        .search-input {
            width: 100%;
            height: 38px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0 14px 0 38px;
            font-size: 13px;
            color: var(--text);
            outline: 0;
            box-shadow: var(--shadow);
        }

        .search-input::placeholder {
            color: var(--muted);
        }

        /* Table Card styles */
        .table-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th, td {
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid #edf2fb;
            white-space: nowrap;
        }

        th {
            background: #f9fbff;
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody tr {
            transition: background 0.15s ease;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .trx-id {
            font-weight: 600;
            color: var(--text);
        }

        .payment-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .payment-badge.cash {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .payment-badge.card {
            background: #e3f2fd;
            color: #1565c0;
        }

        .action-btns {
            display: flex;
            gap: 12px;
        }

        .action-icon {
            color: var(--blue);
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: transform 0.1s ease;
            display: inline-block;
        }

        .action-icon:hover {
            transform: scale(1.15);
        }

        .no-records {
            text-align: center;
            padding: 30px;
            color: var(--muted);
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="app">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">P</div>
                <div class="brand-copy">
                    <strong>Pos System</strong>
                    <span>System Cashier</span>
                </div>
            </div>

            <nav class="nav" aria-label="Sidebar navigation">
                <a href="{{ route('dashboard') }}"><span class="icon">▣</span><span>POS / Cashier</span></a>
                <a href="{{ route('inventory.index') }}"><span class="icon">▥</span><span>Inventory Management</span></a>
                <a class="active" href="{{ route('sales.index') }}"><span class="icon">◷</span><span>Sales Menu</span></a>
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

        <!-- Main Workspace -->
        <main class="workspace">
            <!-- Header Section -->
            <div class="page-head">
                <div class="title">
                    <h1>Transaction History</h1>
                    <p>View and manage sales history</p>
                </div>

                <div class="head-meta">
                    <!-- Notification Bell -->
                    <button class="bell-btn" type="button" aria-label="Notifications">🔔</button>

                    <!-- Clock Widget -->
                    <div class="meta-card">
                        <div>
                            <small>Time</small>
                            <strong id="live-clock">15:07:14 WIB</strong>
                        </div>
                    </div>

                    <!-- Cashier profile -->
                    <div class="user-card">
                        <div class="avatar">👩</div>
                        <div>
                            <small>Cashier</small>
                            <strong>{{ auth()->user()->name ?? 'Hi Shayda' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Summary Cards -->
            <section class="stats-grid">
                <div class="stats-card green">
                    <div class="stats-icon-wrapper">🛒</div>
                    <div class="stats-info">
                        <label>total Transactions</label>
                        <strong id="total-transactions-count">{{ count($transactions) }}</strong>
                    </div>
                </div>

                @php
                    $totalSalesAmount = 0;
                    foreach ($transactions as $trx) {
                        $totalSalesAmount += $trx['total_payment'];
                    }
                    // Format to Iraqi Dinar like: IQD 218.000 or similar
                    $formattedTotalSales = 'IQD ' . number_format($totalSalesAmount, 0, '.', '.');
                @endphp

                <div class="stats-card blue">
                    <div class="stats-icon-wrapper">💵</div>
                    <div class="stats-info">
                        <label>Total Sales</label>
                        <strong id="total-sales-value">{{ $formattedTotalSales }}</strong>
                    </div>
                </div>
            </section>

            <!-- Search and filters -->
            <div class="toolbar">

                <div class="search-wrap">
                    <span>⌕</span>
                    <input class="search-input" type="search" id="sales-search" placeholder="Search transaction ID , cashier name or product name...">
                </div>
            </div>

            <!-- Transaction Table -->
            <section class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Date/Time</th>
                            <th>Cashier name</th>
                            <th>Total items</th>
                            <th>Total payment</th>
                            <th>payment method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="sales-tbody">
                        @if(count($transactions) === 0)
                            <tr>
                                <td colspan="7" class="no-records">No transactions recorded yet.</td>
                            </tr>
                        @endif
                        @foreach ($transactions as $trx)
                            <tr data-trx-id="{{ strtolower($trx['id']) }}"
                                data-cashier="{{ strtolower($trx['cashier_name']) }}"
                                data-date="{{ $trx['date_time'] }}">
                                <td class="trx-id">{{ $trx['id'] }}</td>
                                <td>{{ $trx['date_time'] }}</td>
                                <td>{{ $trx['cashier_name'] }}</td>
                                <td>{{ $trx['total_items'] }}</td>
                                <td>IQD {{ number_format($trx['total_payment'], 0, '.', '.') }}</td>
                                <td>
                                    <span class="payment-badge {{ strtolower($trx['payment_method']) }}">
                                        {{ $trx['payment_method'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <span class="action-icon" title="View details" onclick="viewDetails('{{ $trx['id'] }}')">👁</span>
                                        <span class="action-icon" title="Print receipt" onclick="printReceipt('{{ $trx['id'] }}')">🖨</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Update clock in format HH:MM:SS DAY (or similar)
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            if (!clockEl) return;

            const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const dayName = days[now.getDay()];

            clockEl.textContent = `${hours}:${minutes}:${seconds} ${dayName}`;
        }
        setInterval(updateClock, 1000);
        updateClock(); // Initial run

        // Filtering
        const searchInput = document.getElementById('sales-search');
        const rows = Array.from(document.querySelectorAll('#sales-tbody tr')).filter(r => !r.querySelector('.no-records'));

        function applyFilters() {
            const query = searchInput.value.trim().toLowerCase();

            rows.forEach(row => {
                const trxId = row.dataset.trxId;
                const cashier = row.dataset.cashier;

                const matchesSearch = !query || trxId.includes(query) || cashier.includes(query);
                row.style.display = matchesSearch ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', applyFilters);

        // Action placeholders
        function viewDetails(trxId) {
            alert(`Viewing transaction details for ${trxId}`);
        }

        function printReceipt(trxId) {
            alert(`Printing receipt for ${trxId}`);
        }
    </script>
</body>
</html>
