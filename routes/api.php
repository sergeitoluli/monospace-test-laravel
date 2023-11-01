<?php

use App\Http\Controllers\Api\VesselController;
use App\Http\Controllers\Api\VesselOpexController;
use App\Http\Controllers\Api\VoyageController;
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

Route::post('voyages', [VoyageController::class, 'store']);
Route::put('voyages/{voyageId}', [VoyageController::class, 'update']);
Route::delete('voyages/{voyageId}', [VoyageController::class, 'destroy']);

Route::post('vessels/{vesselId}/vessel-opex', [VesselOpexController::class, 'store']);
Route::get('vessels/{vesselId}/financial-report', [VesselOpexController::class, 'index']);

Route::post('vessels/{vesselId}/update', [VesselController::class, 'update']);