<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('home');
});*/

//Auth::routes();

/**
 * csocsó játék
 */
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * formok
 */
//játékos hozzáadás form
Route::get('/addGamer', [App\Http\Controllers\CsocsoController::class, 'gamerForm'])->name('addGamer');
//csapat hozzáadás form
Route::get('/addTeam', [App\Http\Controllers\CsocsoController::class, 'addTeam'])->name('addTeam');
//bajnokság  hozzáadása form
Route::get('/championship', [App\Http\Controllers\CsocsoController::class, 'championship'])->name('championship');
//toplisták bajnokság választás
Route::get('/top_list', [App\Http\Controllers\CsocsoController::class, 'topList'])->name('topList');
//toplisták listázása a bajnokság alapján
Route::get('/toplistCreated', [App\Http\Controllers\CsocsoController::class, 'toplistCreated'])->name('toplistCreated');
//bajnokság kiválasztása
Route::get('/championshipSelect', [App\Http\Controllers\CsocsoController::class, 'championshipSelect'])->name('championshipSelect');
//meccs kiválasztása
Route::get('/matchSelect', [App\Http\Controllers\CsocsoController::class, 'matchSelect'])->name('matchSelect');
//meccs szerkesztés
Route::get('/matchSetSaveForm', [App\Http\Controllers\CsocsoController::class, 'matchSetSaveForm'])->name('matchSetSaveForm');

/**
 * postok
 */
//játékos mentése
Route::post('/addGamerSave', [App\Http\Controllers\CsocsoController::class, 'gamerFormSave'])->name('gamerFormSave');
//csapat mentése
Route::post('/addTeamSave', [App\Http\Controllers\CsocsoController::class, 'teamFormSave'])->name('teamFormSave');
//bajnokság mentése
Route::post('/championshipFormSave', [App\Http\Controllers\CsocsoController::class, 'championshipFormSave'])->name('championshipFormSave');
//Játékos set
Route::post('/gamerset', [App\Http\Controllers\CsocsoController::class, 'gamerset'])->name('gamerset');
//játékos törlése
Route::post('/gamerdelete', [App\Http\Controllers\CsocsoController::class, 'gamerdelete'])->name('gamerdelete');
//csapat szerkesztés
Route::post('/teamSet', [App\Http\Controllers\CsocsoController::class, 'teamSet'])->name('teamSet');
//meccs mentése
Route::post('/matchSetSave', [App\Http\Controllers\CsocsoController::class, 'matchSetSave'])->name('matchSetSave');