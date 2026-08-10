<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
    
    $deductions = session('stock_deductions', []);
    foreach ($allProducts as &$product) {
        if (isset($deductions[$product['code']])) {
            $product['stock'] = max(0, $product['stock'] - $deductions[$product['code']]);
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

Route::get('/dashboard', function () use ($getAllProducts) {
    return view('dashboard', [
        'products' => $getAllProducts(),
    ]);
})->middleware('auth')->name('dashboard');

Route::post('/inventory-management/add', function () use ($getAllProducts) {
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
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/products'), $filename);
        $imagePath = '/images/products/' . $filename;
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
            $numSizes = (int)$matches[1];
            $numColors = (int)$matches[2];
            
            // Standard size options to select from
            $allSizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL'];
            $sizes = array_slice($allSizes, 0, $numSizes);
            
            $allColors = ['Black', 'White', 'Blue', 'Navy', 'Gray', 'Red', 'Green'];
            $colors = array_slice($allColors, 0, $numColors);
        }

        $stock = (int)($product['stock'] ?? 0);
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
                    $row[] = (int)$val;
                }
                $matrix[$sizeName] = $row;
            }
        }

        $detail = [
            'sizes' => $sizes,
            'colors' => $colors,
            'matrix' => $matrix
        ];
    }

    return view('inventory.show', [
        'product' => $product,
        'detail' => $detail,
    ]);
})->middleware('auth')->name('inventory.show');

$getAllTransactions = function () use ($defaultTransactions) {
    $sessionTransactions = session('transactions', []);
    return array_merge($defaultTransactions, $sessionTransactions);
};

Route::get('/sales-menu', function () use ($getAllTransactions) {
    return view('sales.index', [
        'transactions' => array_reverse($getAllTransactions()),
    ]);
})->middleware('auth')->name('sales.index');

Route::post('/sales-menu/save', function () use ($getAllTransactions) {
    $validated = request()->validate([
        'total_items' => 'required|integer|min:1',
        'total_payment' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:cash,card,Cash,Card',
        'items' => 'nullable|array'
    ]);

    $allTransactions = $getAllTransactions();
    $nextIdNum = count($allTransactions) + 1;
    $id = 'TRX-2026-' . str_pad($nextIdNum, 3, '0', STR_PAD_LEFT);

    $newTransaction = [
        'id' => $id,
        'date_time' => now()->timezone('Asia/Baghdad')->format('d/m/Y H:i'),
        'cashier_name' => auth()->user()->name ?? 'Hi Shayda',
        'total_items' => (int) $validated['total_items'],
        'total_payment' => (float) $validated['total_payment'],
        'payment_method' => strtolower($validated['payment_method']),
    ];

    $sessionTransactions = session('transactions', []);
    $sessionTransactions[] = $newTransaction;
    session(['transactions' => $sessionTransactions]);

    // Store stock deductions
    $items = request('items', []);
    if (!empty($items)) {
        $deductions = session('stock_deductions', []);
        foreach ($items as $item) {
            $sku = $item['sku'] ?? null;
            $qty = $item['qty'] ?? 0;
            if ($sku && $qty > 0) {
                if (!isset($deductions[$sku])) {
                    $deductions[$sku] = 0;
                }
                $deductions[$sku] += $qty;
            }
        }
        session(['stock_deductions' => $deductions]);
    }

    return response()->json(['success' => true, 'transaction' => $newTransaction]);
})->middleware('auth')->name('sales.save');

