<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAnalyticsController;

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

Route::post('/create-property', [GoogleAnalyticsController::class, 'createProperty']);
Route::post('/create-webdatastream-for-property', [GoogleAnalyticsController::class, 'createWebDatastreamForProperty']);
Route::post('/create-property-and-webdatastream', [GoogleAnalyticsController::class, 'createPropertyAndWebDataStream']);
Route::post('/list-all-properties', [GoogleAnalyticsController::class, 'listAllProperties']);
Route::post('/list-acc-emails', [GoogleAnalyticsController::class, 'listAccEmails']);
Route::post('/add-user-access-to-property', [GoogleAnalyticsController::class, 'addUserAccessToProperty']);

//When we want to http://127.0.0.1:8000/property-access.html, we have no parent to post
Route::get('/list-all-properties', [GoogleAnalyticsController::class, 'listAllProperties']);
