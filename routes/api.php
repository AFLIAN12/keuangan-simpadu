<?php

use App\Http\Controllers\KategoriUKTController;
use App\Http\Controllers\KeuTagihanController;
use App\Http\Controllers\KeuKeringananController;

Route::apiResource('kategori-ukt', KategoriUKTController::class);
Route::apiResource('tagihan', KeuTagihanController::class);
Route::apiResource('keringanan', KeuKeringananController::class);
Route::get('tagihan/{id}/nominal-akhir', [\App\Http\Controllers\KeuTagihanController::class, 'nominalAkhir']);