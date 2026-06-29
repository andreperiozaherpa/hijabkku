<?php

use App\Models\DataBarang;
use App\Models\FotoBarang;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);
    Storage::fake('public');

    // Create a product
    $this->product = DataBarang::create([
        'kode' => 'BRG-TEST',
        'jenis_barang' => 'Hijab',
        'nama_barang' => 'Test Hijab Premium',
        'harga_beli' => 50000,
        'harga_jual' => 85000,
        'harga_grosir' => 75000,
    ]);

    // Create an authorized user
    $this->admin = User::factory()->create([
        'status' => 'on',
        'role' => 'admin',
        'shift' => 0,
    ]);
});

test('unauthorized user cannot access product photo index', function () {
    $guest = User::factory()->create(['role' => 'kasir']);
    $response = $this->actingAs($guest)->get('/manajemen/barang/foto');
    $response->assertStatus(403);
});

test('authorized user can access product photo index', function () {
    $response = $this->actingAs($this->admin)->get('/manajemen/barang/foto');
    $response->assertStatus(200);
});

test('user with custom role and kelola_detail_produk permission can access', function () {
    $user = User::factory()->create([
        'status' => 'on',
        'role' => 'gudang',
        'shift' => 0,
    ]);

    // Access before permission (should be 403)
    $response = $this->actingAs($user)->get('/manajemen/barang/foto');
    $response->assertStatus(403);

    // Grant permission dynamically
    $permission = DB::table('permissions')->where('name', 'kelola_detail_produk')->first();
    DB::table('role_permissions')->insert([
        'role' => 'gudang',
        'permission_id' => $permission->id,
    ]);

    // Access after permission (should be 200)
    $response2 = $this->actingAs($user)->get('/manajemen/barang/foto');
    $response2->assertStatus(200);
});

test('authorized user can retrieve product photos list', function () {
    $foto = FotoBarang::create([
        'data_barang_id' => $this->product->id,
        'path' => 'storage/uploads/produk/test.jpg',
        'is_main' => true,
        'is_verified' => true,
    ]);

    $response = $this->actingAs($this->admin)->get("/manajemen/barang/foto/show/{$this->product->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $foto->id,
            'data_barang_id' => $this->product->id,
            'path' => 'storage/uploads/produk/test.jpg',
            'is_main' => true,
        ]);
});

test('user can upload a photo and first photo becomes main automatically', function () {
    $file = UploadedFile::fake()->image('hijab.jpg');

    $response = $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/upload', [
        'data_barang_id' => $this->product->id,
        'file' => $file,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('foto_barangs', [
        'data_barang_id' => $this->product->id,
        'is_main' => true,
        'user_id' => $this->admin->id,
    ]);

    $foto = FotoBarang::where('data_barang_id', $this->product->id)->first();
    Storage::disk('public')->assertExists(str_replace('storage/', '', $foto->path));

    // Also check product table has main photo path
    $this->assertDatabaseHas('data_barangs', [
        'id' => $this->product->id,
        'foto' => $foto->path,
    ]);
});

test('user can upload multiple photos and subsequent ones are not main', function () {
    // First upload
    $file1 = UploadedFile::fake()->image('hijab1.jpg');
    $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/upload', [
        'data_barang_id' => $this->product->id,
        'file' => $file1,
    ]);

    // Second upload
    $file2 = UploadedFile::fake()->image('hijab2.jpg');
    $response = $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/upload', [
        'data_barang_id' => $this->product->id,
        'file' => $file2,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseCount('foto_barangs', 2);

    $first = FotoBarang::orderBy('id', 'asc')->first();
    $second = FotoBarang::orderBy('id', 'desc')->first();

    expect($first->is_main)->toBeTrue();
    expect($second->is_main)->toBeFalse();
});

test('user can change main photo of a product', function () {
    $foto1 = FotoBarang::create([
        'data_barang_id' => $this->product->id,
        'path' => 'storage/uploads/produk/test1.jpg',
        'is_main' => true,
        'is_verified' => true,
    ]);

    $foto2 = FotoBarang::create([
        'data_barang_id' => $this->product->id,
        'path' => 'storage/uploads/produk/test2.jpg',
        'is_main' => false,
        'is_verified' => true,
    ]);

    $response = $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/set-main', [
        'id' => $foto2->id,
    ]);

    $response->assertStatus(200);

    expect($foto1->fresh()->is_main)->toBeFalse();
    expect($foto2->fresh()->is_main)->toBeTrue();

    // Check main product table is updated
    $this->assertDatabaseHas('data_barangs', [
        'id' => $this->product->id,
        'foto' => $foto2->path,
    ]);
});

test('user can delete photo and next photo becomes main', function () {
    $foto1 = FotoBarang::create([
        'data_barang_id' => $this->product->id,
        'path' => 'storage/uploads/produk/test1.jpg',
        'is_main' => true,
        'is_verified' => true,
    ]);

    $foto2 = FotoBarang::create([
        'data_barang_id' => $this->product->id,
        'path' => 'storage/uploads/produk/test2.jpg',
        'is_main' => false,
        'is_verified' => true,
    ]);

    $response = $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/delete', [
        'id' => $foto1->id,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('foto_barangs', ['id' => $foto1->id]);
    expect($foto2->fresh()->is_main)->toBeTrue();

    // Product table points to the new main photo
    $this->assertDatabaseHas('data_barangs', [
        'id' => $this->product->id,
        'foto' => $foto2->path,
    ]);
});

test('non-admin user uploads photo which requires admin verification', function () {
    $nonAdmin = User::factory()->create([
        'status' => 'on',
        'role' => 'gudang',
        'shift' => 0,
    ]);

    // Grant permission dynamically
    $permission = DB::table('permissions')->where('name', 'kelola_detail_produk')->first();
    DB::table('role_permissions')->insert([
        'role' => 'gudang',
        'permission_id' => $permission->id,
    ]);

    $file = UploadedFile::fake()->image('hijab_unverified.jpg');

    $response = $this->actingAs($nonAdmin)->postJson('/manajemen/barang/foto/upload', [
        'data_barang_id' => $this->product->id,
        'file' => $file,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('foto_barangs', [
        'data_barang_id' => $this->product->id,
        'is_verified' => false,
        'is_main' => false,
    ]);

    // Main photo in product table should still be null
    $this->assertNull($this->product->fresh()->foto);
});

test('admin can verify a pending photo', function () {
    $foto = FotoBarang::create([
        'data_barang_id' => $this->product->id,
        'path' => 'storage/uploads/produk/pending.jpg',
        'is_main' => false,
        'is_verified' => false,
    ]);

    // Non-admin trying to verify should fail
    $nonAdmin = User::factory()->create(['role' => 'gudang']);
    $response1 = $this->actingAs($nonAdmin)->postJson('/manajemen/barang/foto/verify', [
        'id' => $foto->id,
    ]);
    $response1->assertStatus(403);

    // Admin verifies
    $response2 = $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/verify', [
        'id' => $foto->id,
    ]);
    $response2->assertStatus(200);

    expect($foto->fresh()->is_verified)->toBeTrue();
    expect($foto->fresh()->is_main)->toBeTrue(); // Since it is the only verified photo
    expect($this->product->fresh()->foto)->toBe($foto->path);
});

test('authorized user can fetch product photo datatable JSON', function () {
    $response = $this->actingAs($this->admin)->get('/manajemen/barang/foto/data');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);
});

test('authorized user can update product description', function () {
    $response = $this->actingAs($this->admin)->postJson('/manajemen/barang/foto/update-desc', [
        'id' => $this->product->id,
        'deskripsi' => 'This is a new test product description.',
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('data_barangs', [
        'id' => $this->product->id,
        'deskripsi' => 'This is a new test product description.',
    ]);
});
