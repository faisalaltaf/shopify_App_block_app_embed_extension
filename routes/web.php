<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controller\CheckembadController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/embed','CheckembadController@index')->middleware(['verify.shopify']);

Route::get('/', function () {
    return view('welcome');
})->middleware(['verify.shopify'])->name('home');
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);