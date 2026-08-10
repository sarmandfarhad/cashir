<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to login when accessing sales menu', function () {
    $response = $this->get(route('sales.index'));

    $response->assertRedirect('/login');
});

test('authenticated user can view sales menu', function () {
    $this->seed(DatabaseSeeder::class);
    $user = User::whereEmail('noor@gmail.com')->firstOrFail();

    $response = $this->actingAs($user)->get(route('sales.index'));

    $response->assertOk()
        ->assertViewIs('sales.index')
        ->assertViewHas('transactions');
});

test('Noor can save each payment method with receipt data and stock deductions', function (string $paymentMethod) {
    $this->seed(DatabaseSeeder::class);
    $user = User::whereEmail('noor@gmail.com')->firstOrFail();

    $response = $this->actingAs($user)->postJson(route('sales.save'), [
        'total_items' => 2,
        'subtotal' => 50000,
        'discount' => 5000,
        'total_payment' => 45000,
        'amount_paid' => 50000,
        'change_due' => 999999,
        'payment_method' => $paymentMethod,
        'items' => [
            [
                'sku' => 'TS-001',
                'name' => 'Basic T-shirt',
                'price' => 25000,
                'qty' => 2,
            ],
        ],
        'receipt' => [
            'printed' => true,
            'notes' => 'Customer copy',
        ],
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'transaction' => [
                'cashier_name' => 'Noor',
                'total_items' => 2,
                'subtotal' => 50000,
                'discount' => 5000,
                'total_payment' => 45000,
                'amount_paid' => 50000,
                'change_due' => 5000,
                'payment_method' => $paymentMethod,
                'items' => [
                    [
                        'sku' => 'TS-001',
                        'name' => 'Basic T-shirt',
                        'price' => 25000,
                        'qty' => 2,
                        'line_total' => 50000,
                    ],
                ],
                'receipt' => [
                    'cashier_name' => 'Noor',
                    'printed' => true,
                    'notes' => 'Customer copy',
                    'total' => 45000,
                    'amount_paid' => 50000,
                    'change_due' => 5000,
                    'payment_method' => $paymentMethod,
                ],
            ],
        ])
        ->assertJsonPath('transaction.receipt.number', 'TRX-2026-006')
        ->assertJsonPath('transaction.receipt.items.0.sku', 'TS-001');

    $sessionTransactions = session('transactions', []);
    expect($sessionTransactions)->toHaveCount(1);
    expect($sessionTransactions[0]['payment_method'])->toBe($paymentMethod)
        ->and($sessionTransactions[0]['change_due'])->toBe(5000.0)
        ->and(session('stock_deductions.TS-001'))->toBe(2);

    $this->get(route('inventory.index'))
        ->assertOk()
        ->assertViewHas('products', function (array $products): bool {
            $product = collect($products)->firstWhere('code', 'TS-001');

            return $product['stock'] === 48;
        });
})->with([
    'cash' => 'cash',
    'card' => 'card',
    'mobile pay' => 'mobile_pay',
]);

test('unsupported payment methods are rejected', function () {
    $this->seed(DatabaseSeeder::class);
    $user = User::whereEmail('noor@gmail.com')->firstOrFail();

    $response = $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('sales.save'), [
            'total_items' => 1,
            'total_payment' => 25000,
            'payment_method' => 'cheque',
        ]);

    $response->assertRedirect(route('dashboard'));
    expect(session()->has('errors'))->toBeTrue();
});
