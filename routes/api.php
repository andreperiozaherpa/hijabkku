<?php

use App\Http\Controllers\Api\LandingCheckoutController;
use App\Http\Controllers\Api\XenditWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/xendit/webhook', [XenditWebhookController::class, 'handleInvoicePayment']);
Route::post('/xendit/payout-webhook', [XenditWebhookController::class, 'handlePayout']);
Route::post('/landing/checkout', [LandingCheckoutController::class, 'createInvoice']);
