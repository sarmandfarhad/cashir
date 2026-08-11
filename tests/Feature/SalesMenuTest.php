<?php

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(DatabaseSeeder::class);
});

function salePayload(string $paymentMethod = 'cash', ?string $note = 'Customer requested gift wrap'): array
{
    return [
        'total_items' => 2,
        'subtotal' => 50000,
        'discount' => 5000,
        'total_payment' => 45000,
        'amount_paid' => 50000,
        'change_due' => 5000,
        'payment_method' => $paymentMethod,
        'note' => $note,
        'items' => [
            [
                'sku' => 'TS-001',
                'name' => 'Basic T-shirt',
                'category' => 'T-shirt',
                'price' => 25000,
                'qty' => 2,
            ],
        ],
        'receipt' => [
            'printed' => true,
            'notes' => $note,
        ],
    ];
}

test('guest is redirected to login when accessing sales menu', function () {
    $this->get(route('sales.index'))->assertRedirect('/login');
});

test('cashier can view the daily sales page', function () {
    $user = User::query()->whereEmail('noor@gmail.com')->firstOrFail();

    $this->actingAs($user)
        ->get(route('sales.index'))
        ->assertOk()
        ->assertViewIs('sales.index')
        ->assertViewHasAll(['transactions', 'selectedDate']);
});

test('each payment method creates a persistent sale with note, items, and stock deduction', function (string $paymentMethod) {
    $noor = User::query()->whereEmail('noor@gmail.com')->firstOrFail();

    $response = $this->actingAs($noor)->postJson(route('sales.save'), salePayload($paymentMethod));

    $response->assertOk()
        ->assertJsonPath('transaction.cashier_name', 'Noor')
        ->assertJsonPath('transaction.payment_method', $paymentMethod)
        ->assertJsonPath('transaction.note', 'Customer requested gift wrap')
        ->assertJsonPath('transaction.receipt.notes', 'Customer requested gift wrap');

    $this->assertDatabaseHas('sales', [
        'user_id' => $noor->id,
        'cashier_name' => 'Noor',
        'payment_method' => $paymentMethod,
        'note' => 'Customer requested gift wrap',
        'total' => 45000,
    ]);
    $this->assertDatabaseHas('sale_items', [
        'sku' => 'TS-001',
        'quantity' => 2,
        'line_total' => 50000,
    ]);

    $this->actingAs($noor)
        ->get(route('dashboard'))
        ->assertViewHas('products', fn (array $products) => collect($products)->firstWhere('code', 'TS-001')['stock'] === 48);
})->with([
    'cash' => 'cash',
    'card' => 'card',
    'mobile pay' => 'mobile_pay',
]);

test('a sale made by Yousef is visible to Noor on the shared daily sales page', function () {
    $yousef = User::query()->whereEmail('yousef@gmail.com')->firstOrFail();
    $noor = User::query()->whereEmail('noor@gmail.com')->firstOrFail();

    $this->actingAs($yousef)
        ->postJson(route('sales.save'), salePayload(note: 'Yousef shift sale'))
        ->assertOk();

    $this->actingAs($noor)
        ->get(route('sales.index'))
        ->assertOk()
        ->assertSee('Yousef')
        ->assertSee('Yousef shift sale');
});

test('daily sales filter only returns records for the selected Baghdad date', function () {
    $user = User::query()->whereEmail('noor@gmail.com')->firstOrFail();

    $this->actingAs($user)->postJson(route('sales.save'), salePayload())->assertOk();
    $sale = Sale::query()->firstOrFail();

    $this->actingAs($user)
        ->get(route('sales.index', ['date' => $sale->sold_at->timezone('Asia/Baghdad')->format('Y-m-d')]))
        ->assertSee($sale->number);

    $this->actingAs($user)
        ->get(route('sales.index', ['date' => $sale->sold_at->copy()->addDay()->format('Y-m-d')]))
        ->assertDontSee($sale->number);
});

test('server rejects an order that exceeds persisted stock', function () {
    $user = User::query()->whereEmail('noor@gmail.com')->firstOrFail();
    $payload = salePayload();
    $payload['items'][0]['qty'] = 51;
    $payload['total_items'] = 51;

    $this->actingAs($user)
        ->postJson(route('sales.save'), $payload)
        ->assertUnprocessable();

    expect(Sale::query()->count())->toBe(0)
        ->and(SaleItem::query()->count())->toBe(0);
});

test('unsupported payment methods are rejected', function () {
    $user = User::query()->whereEmail('noor@gmail.com')->firstOrFail();
    $payload = salePayload('cheque');

    $this->actingAs($user)
        ->postJson(route('sales.save'), $payload)
        ->assertUnprocessable();
});
