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
            padding: 18px 12px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px 14px;
            border-bottom: 1px solid #edf2fb;
        }

        .mark {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--blue);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 700;
        }

        .brand strong,
        .brand span {
            display: block;
            line-height: 1.15;
        }

        .brand strong { font-size: 13px; }
        .brand span { font-size: 10px; color: var(--muted); margin-top: 3px; }

        .nav {
            display: grid;
            gap: 8px;
            margin-top: 16px;
        }

        .nav a {
            text-decoration: none;
            color: var(--text);
            font-size: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav a.active {
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: 700;
        }

        .logout-wrap {
            margin-top: auto;
            border-top: 1px solid #edf2fb;
            padding-top: 12px;
        }

        .logout-wrap button {
            border: 0;
            background: transparent;
            color: var(--red);
            font-size: 12px;
            padding: 8px 10px;
            cursor: pointer;
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
                <div class="mark">P</div>
                <div>
                    <strong>Pos System</strong>
                    <span>System Cashier</span>
                </div>
            </div>

            <nav class="nav" aria-label="Main navigation">
                <a href="{{ route('dashboard') }}">POS / Cashier</a>
                <a class="active" href="{{ route('inventory.index') }}">Inventory Management</a>
                <a href="{{ route('dashboard') }}">Sales Menu</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('dashboard') }}">Reports</a>
                <a href="{{ route('dashboard') }}">Setting</a>
            </nav>

            <div class="logout-wrap">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
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
