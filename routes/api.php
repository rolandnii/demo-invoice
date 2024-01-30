<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Middleware\AuthenticateApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return 'something nice';
// });

Route::controller(AuthenticationController::class)->group(function () {

    Route::post('login', 'login');
    Route::post('register','register');
    Route::post('admin/register','adminRegister');
});

Route::middleware(AuthenticateApi::class)->group(function () {
    //Invoice
    Route::prefix('invoice')->group(function () {
        Route::get('/{invoice_id}', [InvoiceController::class, 'show']);
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/', [InvoiceController::class, 'store']);
        Route::delete('/{invoice_id}', [InvoiceController::class, 'destroy']);
        Route::get('customer/{cutomer_id}', [InvoiceController::class, 'showCustomerInvoices']);
    });


    //Item
    Route::prefix('item')->group(function () {
        Route::get('/{item_code}', [ItemController::class, 'show']);
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::delete('/{item_code}', [ItemController::class, 'destroy']);
        Route::post('update/{item_code}', [ItemController::class, 'update']);
    });
});

Route::fallback(FallbackController::class);
