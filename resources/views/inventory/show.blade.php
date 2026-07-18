<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Item Detail</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --panel: #ffffff;
            --line: #dbe3ef;
            --text: #182235;
            --muted: #71809a;
            --blue: #315fd1;
            --blue-soft: #eef4ff;
            --red: #cb4d4d;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 210px minmax(0, 1fr);
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
            border-bottom: 1px solid #edf2fb;
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

        .brand-copy strong { font-size: 14px; }
        .brand-copy span { font-size: 11px; color: var(--muted); margin-top: 4px; }

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
            border-top: 1px solid #edf2fb;
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

        .main {
            padding: 20px;
        }

        .title h1 { margin: 0; font-size: 26px; }
        .title p { margin: 4px 0 14px; color: var(--muted); font-size: 12px; }

        .header-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .back {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            min-height: 34px;
            padding: 0 12px;
            font-size: 12px;
            text-decoration: none;
            color: var(--text);
            display: inline-flex;
            align-items: center;
        }

        .product-chip {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            min-height: 34px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            font-size: 13px;
            font-weight: 700;
        }

        .desc {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            width: min(620px, 100%);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #edf2fb;
            border-right: 1px solid #edf2fb;
        }

        th:first-child,
        td:first-child { text-align: left; }

        th {
            background: #f3f7ff;
            color: #4f607f;
            font-size: 11px;
        }

        td.low { color: var(--red); }

        tr:last-child td {
            font-weight: 700;
            background: #fafcff;
        }
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
                <a href="{{ route('dashboard') }}"><span class="icon">▣</span><span>POS / Cashier</span></a>
                <a class="active" href="{{ route('inventory.index') }}"><span class="icon">▥</span><span>Inventory Management</span></a>
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

        <main class="main">
            <div class="title">
                <h1>Product List</h1>
                <p>Manage Products & Inventory</p>
            </div>

            <div class="header-row">
                <a class="back" href="{{ route('inventory.index') }}">Back</a>
                <div class="product-chip">{{ $product['name'] }}</div>
            </div>

            <div class="desc">
                Sizes: {{ implode(' - ', $detail['sizes']) }}<br>
                Colors: {{ implode(' - ', $detail['colors']) }}
            </div>

            <section class="panel">
                <table>
                    <thead>
                        <tr>
                            <th>Size</th>
                            @foreach ($detail['colors'] as $color)
                                <th>{{ $color }}</th>
                            @endforeach
                            <th>Total</th>
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
                            <td>Total</td>
                            @foreach ($colorTotals as $total)
                                <td>{{ $total }}</td>
                            @endforeach
                            <td>{{ $grandTotal }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
