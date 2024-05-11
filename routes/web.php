<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use Illuminate\Http\Request;

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
    return view('welcome');
})->name('mainPage');

Route::get('/edit/{id}', function (string $id) {
    return view('edit', ['id'=> $id]);
})->name('editPage');

Route::get('/add/{id}', function (string $id) {
    return view('addImg', ['id'=> $id]);
})->name('addImgPage');

Route::get('/pet', [PetController::class, 'getPetById'])->name('pet.getById');
Route::get('/pet/findByStatus', [PetController::class, 'getPetByStatus'])->name('pet.getByStatus');
Route::post('/pet/{id}/uploadImage', [PetController::class, 'storeImage'])->name('pet.uploadImage');
Route::post('/pet/{id}', [PetController::class, 'edit'])->name('pet.edit');
Route::post('/pet', [PetController::class, 'store'])->name('pet.create');
Route::put('/pet', [PetController::class, 'update'])->name('pet.fullUpdate');
Route::delete('/pet/{id}', [PetController::class, 'destroy'])->name('pet.destroy');
