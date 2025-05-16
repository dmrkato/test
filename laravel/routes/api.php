<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
   Route::prefix('comment')->group(function () {
       Route::post('', [\App\Http\Controllers\API\V1\CommentController::class, 'create']);
   });
});
