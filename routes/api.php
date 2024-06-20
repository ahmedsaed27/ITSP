<?php

use App\Http\Controllers\Api\V1\Applicant\Applicant;
use App\Http\Controllers\Api\V1\ContactUs\ContactUs;
use App\Http\Controllers\Api\V1\Jobs\Jobs;
use App\Http\Controllers\Api\V1\Projects\Projects;
use App\Http\Controllers\Api\V1\Reels\Reels;
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



Route::middleware('throttle' , 'api')->group(function(){
    Route::apiResource('projects' , Projects::class)->except('create' , 'update' , 'show', 'destroy');
    Route::apiResource('applicant' , Applicant::class);
    Route::apiResource('jobs' , Jobs::class);
    Route::apiResource('reels' , Reels::class)->except('create' , 'update' , 'show', 'destroy');
    Route::apiResource('contact' , ContactUs::class);

    Route::controller(Jobs::class)->group(function(){
        Route::get('job/level'  , 'jobLevel');
        Route::get('job/type' , 'jobType');
        Route::get('job/place' , 'jobPlace');
    });


});

