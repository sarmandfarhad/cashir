<?php

use App\Http\Controllers\AuthController;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

$inventoryProducts = [
    ['code' => 'TS-001', 'name' => 'Basic T-shirt', 'category' => 'T-shirt', 'variants' => '4 S + 3 C', 'stock' => 50, 'min_stock' => 20, 'price' => 25000],
    ['code' => 'TS-002', 'name' => 'Oversized T-shirt', 'category' => 'T-shirt', 'variants' => '4 S + 2 C', 'stock' => 38, 'min_stock' => 10, 'price' => 30000],
    ['code' => 'PO-001', 'name' => 'Polo Shirt', 'category' => 'Polo shirt', 'variants' => 'Free S + 3 C', 'stock' => 10, 'min_stock' => 5, 'price' => 45000],
    ['code' => 'HD-001', 'name' => 'Pullover Hoodie', 'category' => 'Hoodie', 'variants' => '4 S + 4 C', 'stock' => 18, 'min_stock' => 10, 'price' => 15000],
    ['code' => 'JK-001', 'name' => 'Denim Jacket', 'category' => 'Jacket', 'variants' => '4 S + 2 C', 'stock' => 5, 'min_stock' => 5, 'price' => 25000],
    ['code' => 'SH-001', 'name' => 'Formal Shirt', 'category' => 'Shirt', 'variants' => '5 S + 3 C', 'stock' => 25, 'min_stock' => 13, 'price' => 30000],
    ['code' => 'PO-002', 'name' => 'Polo Shirt', 'category' => 'Polo shirt', 'variants' => '3 S + 2 C', 'stock' => 1, 'min_stock' => 3, 'price' => 45000],
    ['code' => 'CP-001', 'name' => 'Cargo Pants', 'category' => 'Pants', 'variants' => '5 S + 3 C', 'stock' => 5, 'min_stock' => 3, 'price' => 17000],
];

$inventoryDetail = [
    'TS-001' => [
        'sizes' => ['S', 'M', 'L', 'XL'],
        'colors' => ['Black', 'White', 'Blue'],
        'matrix' => [
            'S' => [5, 4, 3],
            'M' => [6, 5, 4],
            'L' => [7, 6, 5],
            'XL' => [2, 2, 1],
        ],
    ],
    'TS-002' => [
        'sizes' => ['S', 'M', 'L', 'XL'],
        'colors' => ['Black', 'White'],
        'matrix' => [
            'S' => [4, 3],
            'M' => [6, 5],
            'L' => [7, 4],
            'XL' => [5, 4],
        ],
    ],
    'PO-001' => [
        'sizes' => ['Free'],
        'colors' => ['Navy', 'White', 'Red'],
        'matrix' => [
            'Free' => [4, 3, 3],
        ],
    ],
    'HD-001' => [
        'sizes' => ['S', 'M', 'L', 'XL'],
        'colors' => ['Gray', 'Black', 'Navy', 'Green'],
        'matrix' => [
            'S' => [1, 1, 1, 0],
            'M' => [2, 2, 1, 1],
            'L' => [2, 1, 2, 1],
            'XL' => [1, 1, 0, 1],
        ],
    ],
    'JK-001' => [
        'sizes' => ['S', 'M', 'L', 'XL'],
        'colors' => ['Blue', 'Black'],
        'matrix' => [
            'S' => [1, 0],
            'M' => [1, 1],
            'L' => [1, 0],
            'XL' => [0, 1],
        ],
    ],
    'SH-001' => [
        'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
        'colors' => ['White', 'Blue', 'Gray'],
        'matrix' => [
            'S' => [2, 1, 1],
            'M' => [3, 2, 2],
            'L' => [2, 3, 2],
            'XL' => [2, 1, 2],
            'XXL' => [1, 0, 1],
        ],
    ],
    'PO-002' => [
        'sizes' => ['M', 'L', 'XL'],
        'colors' => ['White', 'Black'],
        'matrix' => [
            'M' => [0, 0],
            'L' => [1, 0],
            'XL' => [0, 0],
        ],
    ],
    'CP-001' => [
        'sizes' => ['28', '30', '32', '34', '36'],
        'colors' => ['Khaki', 'Olive', 'Black'],
        'matrix' => [
            '28' => [0, 1, 0],
            '30' => [1, 0, 1],
            '32' => [1, 0, 0],
            '34' => [0, 1, 0],
            '36' => [0, 0, 0],
        ],
    ],
];

$defaultTransactions = [
    [
        'id' => 'TRX-2025-001',
        'date_time' => '01/06/2026 12:00',
        'cashier_name' => 'name1',
        'total_items' => 2,
        'total_payment' => 115000,
        'payment_method' => 'cash',
    ],
    [
        'id' => 'TRX-2025-002',
        'date_time' => '01/06/2026 15:00',
        'cashier_name' => 'name2',
        'total_items' => 3,
        'total_payment' => 48000,
        'payment_method' => 'card',
    ],
    [
        'id' => 'TRX-2025-003',
        'date_time' => '01/06/2026 15:30',
        'cashier_name' => 'name3',
        'total_items' => 6,
        'total_payment' => 18000,
        'payment_method' => 'cash',
    ],
    [
        'id' => 'TRX-2025-003',
        'date_time' => '01/06/2026 15:40',
        'cashier_name' => 'name4',
        'total_items' => 3,
        'total_payment' => 12000,
        'payment_method' => 'card',
    ],
    [
        'id' => 'TRX-2025-003',
        'date_time' => '01/06/2026 19:00',
        'cashier_name' => 'name5',
        'total_items' => 5,
        'total_payment' => 25000,
        'payment_method' => 'card',
    ],
];

$getAllProducts = function () use ($inventoryProducts) {
    $sessionProducts = session('products', []);
    $allProducts = array_merge($inventoryProducts, $sessionProducts);

    $deductions = SaleItem::query()
        ->select('sku', DB::raw('SUM(quantity) as quantity_sold'))
        ->groupBy('sku')
        ->pluck('quantity_sold', 'sku');

    foreach ($allProducts as &$product) {
        if (isset($deductions[$product['code']])) {
            $product['stock'] = max(0, $product['stock'] - (int) $deductions[$product['code']]);
        }
    }

    return $allProducts;
};

Route::get('/', function () {
    return view('auth.login');
})->name('login.form');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.page');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::post('/preferences/locale', function (Request $request) {
    $validated = $request->validate([
        'locale' => ['required', 'string', 'in:en,ar'],
    ]);

    $request->session()->put('locale', $validated['locale']);
    app()->setLocale($validated['locale']);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'locale' => $validated['locale'],
        ]);
    }

    return back();
})->name('preferences.locale');

Route::post('/preferences/theme', function (Request $request) {
    $validated = $request->validate([
        'theme' => ['required', 'string', 'in:light,dark'],
    ]);

    $request->session()->put('theme', $validated['theme']);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'theme' => $validated['theme'],
        ]);
    }

    return back();
})->name('preferences.theme');

Route::get('/dashboard', function () use ($getAllProducts) {
    return view('dashboard', [
        'products' => $getAllProducts(),
    ]);
})->middleware('auth')->name('dashboard');

Route::post('/inventory-management/add', function () {
    $validated = request()->validate([
        'name' => 'required|string',
        'code' => 'required|string',
        'category' => 'required|string',
        'variants' => 'required|string',
        'stock' => 'required|numeric|min:0',
        'min_stock' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = '';
    if (request()->hasFile('image')) {
        $image = request()->file('image');
        $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/products'), $filename);
        $imagePath = '/images/products/'.$filename;
    }

    $newProduct = [
        'code' => $validated['code'],
        'name' => $validated['name'],
        'category' => $validated['category'],
        'variants' => $validated['variants'],
        'stock' => (int) $validated['stock'],
        'min_stock' => (int) $validated['min_stock'],
        'price' => (int) $validated['price'],
        'image' => $imagePath,
    ];

    $sessionProducts = session('products', []);
    $sessionProducts[] = $newProduct;
    Session::put('products', $sessionProducts);

    return response()->json(['success' => true, 'product' => $newProduct]);
})->middleware('auth')->name('inventory.add');

Route::get('/inventory-management', function () use ($getAllProducts) {
    return view('inventory.index', [
        'products' => $getAllProducts(),
    ]);
})->middleware('auth')->name('inventory.index');

Route::get('/inventory-management/{code}', function (string $code) use ($getAllProducts, $inventoryDetail) {
    $products = $getAllProducts();
    $product = null;

    foreach ($products as $p) {
        if ($p['code'] === $code) {
            $product = $p;
            break;
        }
    }

    abort_if($product === null, 404);

    // If it exists in hardcoded details, use it
    if (isset($inventoryDetail[$code])) {
        $detail = $inventoryDetail[$code];
    } else {
        // Dynamically build matrix using product variants & stock (e.g. from session/added products)
        $variantsStr = $product['variants'] ?? ''; // e.g. "4 S + 3 C" or "S - M - L | Black"

        $sizes = ['S', 'M', 'L', 'XL'];
        $colors = ['Black', 'White', 'Blue'];

        // Try parsing size count and color count from variants string e.g. "4 S + 3 C"
        if (preg_match('/(\d+)\s*S\s*\+\s*(\d+)\s*C/i', $variantsStr, $matches)) {
            $numSizes = (int) $matches[1];
            $numColors = (int) $matches[2];

            // Standard size options to select from
            $allSizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL'];
            $sizes = array_slice($allSizes, 0, $numSizes);

            $allColors = ['Black', 'White', 'Blue', 'Navy', 'Gray', 'Red', 'Green'];
            $colors = array_slice($allColors, 0, $numColors);
        }

        $stock = (int) ($product['stock'] ?? 0);
        $numRows = count($sizes);
        $numCols = count($colors);
        $totalCells = $numRows * $numCols;

        $matrix = [];
        if ($totalCells > 0) {
            $baseVal = floor($stock / $totalCells);
            $remainder = $stock % $totalCells;

            foreach ($sizes as $rIndex => $sizeName) {
                $row = [];
                for ($cIndex = 0; $cIndex < $numCols; $cIndex++) {
                    $val = $baseVal;
                    if ($remainder > 0) {
                        $val += 1;
                        $remainder--;
                    }
                    $row[] = (int) $val;
                }
                $matrix[$sizeName] = $row;
            }
        }

        $detail = [
            'sizes' => $sizes,
            'colors' => $colors,
            'matrix' => $matrix,
        ];
    }

    return view('inventory.show', [
        'product' => $product,
        'detail' => $detail,
    ]);
})->middleware('auth')->name('inventory.show');

Route::get('/sales-menu', function (Request $request) {
    $validated = $request->validate([
        'date' => ['nullable', 'date_format:Y-m-d'],
    ]);
    $selectedDate = $validated['date'] ?? now('Asia/Baghdad')->format('Y-m-d');
    $dayStart = Carbon::createFromFormat('Y-m-d', $selectedDate, 'Asia/Baghdad')->startOfDay()->utc();
    $dayEnd = $dayStart->copy()->addDay();

    $sales = Sale::query()
        ->with('items')
        ->where('sold_at', '>=', $dayStart)
        ->where('sold_at', '<', $dayEnd)
        ->latest('sold_at')
        ->get();

    $transactions = $sales->map(fn (Sale $sale) => [
        'id' => $sale->number,
        'date_time' => $sale->sold_at->timezone('Asia/Baghdad')->format('d/m/Y H:i'),
        'cashier_name' => $sale->cashier_name,
        'total_items' => $sale->total_items,
        'subtotal' => $sale->subtotal,
        'discount' => $sale->discount,
        'total_payment' => $sale->total,
        'amount_paid' => $sale->amount_paid,
        'change_due' => $sale->change_due,
        'payment_method' => $sale->payment_method,
        'note' => $sale->note,
        'items' => $sale->items->map(fn ($item) => [
            'sku' => $item->sku,
            'name' => $item->name,
            'price' => $item->price,
            'qty' => $item->quantity,
            'line_total' => $item->line_total,
        ])->all(),
    ])->all();

    return view('sales.index', compact('transactions', 'selectedDate'));
})->middleware('auth')->name('sales.index');

Route::post('/sales-menu/save', function () use ($getAllProducts) {
    $validated = request()->validate([
        'total_items' => ['required', 'integer', 'min:1'],
        'subtotal' => ['nullable', 'numeric', 'min:0'],
        'discount' => ['nullable', 'numeric', 'min:0'],
        'total_payment' => ['required', 'numeric', 'min:0'],
        'amount_paid' => ['nullable', 'numeric', 'gte:total_payment'],
        'change_due' => ['nullable', 'numeric', 'min:0'],
        'payment_method' => ['required', 'string', 'in:cash,card,mobile_pay'],
        'items' => ['nullable', 'array'],
        'items.*.sku' => ['required_with:items', 'string', 'max:100'],
        'items.*.name' => ['nullable', 'string', 'max:255'],
        'items.*.category' => ['nullable', 'string', 'max:255'],
        'items.*.price' => ['nullable', 'numeric', 'min:0'],
        'items.*.qty' => ['required_with:items', 'integer', 'min:1'],
        'note' => ['nullable', 'string', 'max:100'],
        'receipt' => ['nullable', 'array'],
        'receipt.printed' => ['nullable', 'boolean'],
        'receipt.notes' => ['nullable', 'string', 'max:100'],
    ]);

    $issuedAt = now()->timezone('Asia/Baghdad');
    $discount = (float) ($validated['discount'] ?? 0);
    $totalPayment = (float) $validated['total_payment'];
    $subtotal = (float) ($validated['subtotal'] ?? ($totalPayment + $discount));
    $amountPaid = (float) ($validated['amount_paid'] ?? $totalPayment);
    $changeDue = max(0, $amountPaid - $totalPayment);
    $items = array_map(
        static fn (array $item): array => [
            'sku' => $item['sku'],
            'name' => $item['name'] ?? $item['sku'],
            'category' => $item['category'] ?? null,
            'price' => (float) ($item['price'] ?? 0),
            'qty' => (int) $item['qty'],
            'line_total' => (float) ($item['price'] ?? 0) * (int) $item['qty'],
        ],
        $validated['items'] ?? [],
    );
    $cashierName = auth()->user()->name ?? 'Cashier';

    $availableProducts = collect($getAllProducts())->keyBy('code');
    foreach ($items as $item) {
        $product = $availableProducts->get($item['sku']);
        if (! $product || $item['qty'] > (int) $product['stock']) {
            return response()->json([
                'message' => __('checkout.stock_limit'),
                'errors' => ['items' => [__('checkout.stock_limit')]],
            ], 422);
        }
    }

    $sale = DB::transaction(function () use (
        $amountPaid,
        $cashierName,
        $changeDue,
        $discount,
        $issuedAt,
        $items,
        $subtotal,
        $totalPayment,
        $validated,
    ) {
        $sale = Sale::query()->create([
            'number' => 'TMP-'.Str::uuid(),
            'user_id' => auth()->id(),
            'cashier_name' => $cashierName,
            'total_items' => (int) $validated['total_items'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $totalPayment,
            'amount_paid' => $amountPaid,
            'change_due' => $changeDue,
            'payment_method' => $validated['payment_method'],
            'note' => $validated['note'] ?? $validated['receipt']['notes'] ?? null,
            'receipt_printed' => (bool) ($validated['receipt']['printed'] ?? false),
            'sold_at' => $issuedAt,
        ]);

        $sale->update([
            'number' => 'TRX-'.$issuedAt->format('Y').'-'.str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT),
        ]);

        $sale->items()->createMany(array_map(fn (array $item) => [
            'sku' => $item['sku'],
            'name' => $item['name'],
            'category' => $item['category'],
            'price' => $item['price'],
            'quantity' => $item['qty'],
            'line_total' => $item['line_total'],
        ], $items));

        return $sale->load('items');
    });

    $newTransaction = [
        'id' => $sale->number,
        'date_time' => $sale->sold_at->timezone('Asia/Baghdad')->format('d/m/Y H:i'),
        'cashier_name' => $sale->cashier_name,
        'total_items' => $sale->total_items,
        'subtotal' => $sale->subtotal,
        'discount' => $sale->discount,
        'total_payment' => $sale->total,
        'amount_paid' => $sale->amount_paid,
        'change_due' => $sale->change_due,
        'payment_method' => $sale->payment_method,
        'note' => $sale->note,
        'items' => $items,
        'receipt' => [
            'number' => $sale->number,
            'issued_at' => $sale->sold_at->toIso8601String(),
            'cashier_name' => $sale->cashier_name,
            'printed' => $sale->receipt_printed,
            'notes' => $sale->note,
            'subtotal' => $sale->subtotal,
            'discount' => $sale->discount,
            'total' => $sale->total,
            'amount_paid' => $sale->amount_paid,
            'change_due' => $sale->change_due,
            'payment_method' => $sale->payment_method,
            'items' => $items,
        ],
    ];

    return response()->json(['success' => true, 'transaction' => $newTransaction]);
})->middleware('auth')->name('sales.save');
