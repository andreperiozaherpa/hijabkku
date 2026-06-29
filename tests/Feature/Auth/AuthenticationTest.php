<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('token mismatch exception on login redirects to login page with error', function () {
    Route::post('/login-test-csrf', function () {
        throw new TokenMismatchException;
    })->name('login')->middleware('web');

    $response = $this->post('/login-test-csrf');

    $response->assertRedirectToRoute('login');
    $response->assertSessionHas('error', 'Sesi login Anda telah kedaluwarsa. Silakan coba kembali.');
});

test('token mismatch exception on other routes redirects back with general error', function () {
    Route::post('/other-test-csrf', function () {
        throw new TokenMismatchException;
    })->middleware('web');

    // Make request from a specific referrer page to test redirect()->back()
    $response = $this->from('/some-referrer-page')->post('/other-test-csrf');

    $response->assertRedirect('/some-referrer-page');
    $response->assertSessionHas('error', 'Halaman atau sesi Anda telah kedaluwarsa. Silakan coba kirim ulang formulir.');
});
