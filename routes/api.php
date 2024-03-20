<?php

use App\Http\Controllers\Api\V1\Applicant\Applicant;
use App\Http\Controllers\Api\V1\Projects\Projects;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('throttle')->group(function(){
    Route::apiResource('projects' , Projects::class);
    Route::apiResource('applicant' , Applicant::class);
});

