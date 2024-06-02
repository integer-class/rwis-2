<?php
/* routes\api.php */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetailController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
 
    // Route::get('/user', function (Request $request) {
        
    //     return $request->user();
    // });


    Route::get('/user', [DetailController::class, 'detail']);

});