<?php

use App\Http\Controllers\KeuTagihanController;
use App\Http\Controllers\KeuPembayaranController;
use App\Http\Controllers\KeuKeringananController;
use App\Http\Controllers\KeuLogAktivitasController;

Route::apiResource('tagihan', KeuTagihanController::class);
Route::apiResource('pembayaran', KeuPembayaranController::class);
Route::apiResource('keringanan', KeuKeringananController::class);
Route::apiResource('log-aktivitas', KeuLogAktivitasController::class);
