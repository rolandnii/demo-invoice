<?php

use App\Models\User;
use App\Jobs\SendMail;
use App\Models\Invoice;
use App\Mail\OrderShipped;
use App\Mail\CreateUserMail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FallbackController;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('preview',function() {
    // SendMail::dispatch()->onConnection('redis');
//    $users = Cache::remember('users',now()->addMinutes(2),fn() => User::all());
       Cache::put('guy', fn() => User::all(), now()->addMinute());
    return (new OrderShipped);
}
);
Route::get('shipped',fn() => new OrderShipped);
Route::fallback(FallbackController::class);




// require __DIR__.'/auth.php';
