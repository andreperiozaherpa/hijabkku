<?php

use App\Models\User;
use App\Models\StockToko;
use App\Models\Toko;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('can sort stock toko detail by sisa', function () {
    // Run the RBAC seeder to populate roles and permissions
    $this->seed(\Database\Seeders\RBACSeeder::class);

    // Create a user with admin role and status on
    $user = User::factory()->create([
        'status' => 'on',
        'role' => 'admin',
    ]);

    // Create a Toko manually to bypass mass assignment guard
    $toko = new Toko();
    $toko->kode = 'TK_test';
    $toko->nama_toko = 'Test Toko';
    $toko->save();

    // Create StockToko items with different 'sisa' (jumlah - terjual)
    // Item 1: sisa = 10 - 2 = 8
    $stock1 = new StockToko();
    $stock1->kode_input = 'INPUT_001';
    $stock1->kode_toko = 'TK_test';
    $stock1->kode_barang = 'BRG001';
    $stock1->supplier = 'Supplier A';
    $stock1->nama_barang = 'Barang A';
    $stock1->jumlah = 10;
    $stock1->terjual = 2;
    $stock1->save();

    // Item 2: sisa = 5 - 4 = 1
    $stock2 = new StockToko();
    $stock2->kode_input = 'INPUT_002';
    $stock2->kode_toko = 'TK_test';
    $stock2->kode_barang = 'BRG002';
    $stock2->supplier = 'Supplier B';
    $stock2->nama_barang = 'Barang B';
    $stock2->jumlah = 5;
    $stock2->terjual = 4;
    $stock2->save();

    // Item 3: sisa = 20 - 5 = 15
    $stock3 = new StockToko();
    $stock3->kode_input = 'INPUT_003';
    $stock3->kode_toko = 'TK_test';
    $stock3->kode_barang = 'BRG003';
    $stock3->supplier = 'Supplier C';
    $stock3->nama_barang = 'Barang C';
    $stock3->jumlah = 20;
    $stock3->terjual = 5;
    $stock3->save();

    // Enable Query Log for ASC
    DB::enableQueryLog();

    // Make AJAX request simulating DataTables sorting by 'sisa'
    $response = $this->actingAs($user)
        ->getJson('/manajemen/stock/toko/show/TK_test?' . http_build_query([
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'order' => [
                [
                    'column' => 6,
                    'dir' => 'asc'
                ]
            ],
            'columns' => [
                ['data' => 'null', 'orderable' => 'false', 'searchable' => 'false'],
                ['data' => 'kode_barang', 'orderable' => 'false', 'searchable' => 'true'],
                ['data' => 'supplier', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'nama_barang', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'jumlah', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'terjual', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'sisa', 'orderable' => 'true', 'searchable' => 'true'],
            ]
        ]));

    $response->assertStatus(200);
    $data = $response->json('data');

    // Print queries for ascending
    fwrite(STDERR, "\nASCENDING QUERIES:\n" . var_export(DB::getQueryLog(), true) . "\n");

    // Clear Query Log for desc
    DB::flushQueryLog();

    // Test descending order
    $responseDesc = $this->actingAs($user)
        ->getJson('/manajemen/stock/toko/show/TK_test?' . http_build_query([
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'order' => [
                [
                    'column' => 6,
                    'dir' => 'desc'
                ]
            ],
            'columns' => [
                ['data' => 'null', 'orderable' => 'false', 'searchable' => 'false'],
                ['data' => 'kode_barang', 'orderable' => 'false', 'searchable' => 'true'],
                ['data' => 'supplier', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'nama_barang', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'jumlah', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'terjual', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'sisa', 'orderable' => 'true', 'searchable' => 'true'],
            ]
        ]));

    $responseDesc->assertStatus(200);
    $dataDesc = $responseDesc->json('data');

    // Print queries for descending
    fwrite(STDERR, "\nDESCENDING QUERIES:\n" . var_export(DB::getQueryLog(), true) . "\n");

    // With descending order, the sisa should be 15 (Barang C), 8 (Barang A), 1 (Barang B)
    expect($dataDesc[0]['nama_barang'])->toBe('Barang C');
    expect($dataDesc[1]['nama_barang'])->toBe('Barang A');
    expect($dataDesc[2]['nama_barang'])->toBe('Barang B');
});
