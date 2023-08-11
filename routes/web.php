<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;
use App\Models\Airport;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $airports = Airport::all();
    $stops = ['direct','1','2'];
    return view('landing', compact('airports','stops'));
});

Route::post('/search-flights', [FlightController::class, 'searchFlights']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
