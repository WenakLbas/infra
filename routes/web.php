<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfraImportController;


Route::get('infractions', [InfraImportController::class, 'infractions']);
Route::post('infractions-import', [InfraImportController::class, 'importInfractionsFile']);
Route::get('infractions-export', [InfraImportController::class, 'infractionsExport']);

Route::get('infra-crime', [InfraImportController::class, 'crimeInfrasFile']);
Route::get('infra-violence', [InfraImportController::class, 'violenceInfrasFile']);
Route::get('infra-deontologie', [InfraImportController::class, 'deontologieInfrasFile']);
