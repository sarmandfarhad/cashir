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
];

$getAllProducts = function () use ($inventoryProducts) {
    $sessionProducts = session('products', []);
    return array_merge($inventoryProducts, $sessionProducts);
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

    $detail = $inventoryDetail[$code] ?? [
        'sizes' => ['S', 'M', 'L', 'XL'],
        'colors' => ['Black', 'White', 'Blue'],
        'matrix' => [
            'S' => [1, 1, 1],
            'M' => [1, 1, 1],
            'L' => [1, 1, 1],
            'XL' => [1, 1, 1],
        ],
    ];

    return view('inventory.show', [
        'product' => $product,
        'detail' => $detail,
    ]);
})->middleware('auth')->name('inventory.show');
