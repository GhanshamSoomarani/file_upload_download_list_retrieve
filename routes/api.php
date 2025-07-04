<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\controllers\FileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/upload', [FileController::class, 'fileUpload']);
Route::get('/listfiles', [FileController::class, 'listFiles']);
Route::get('/files/{id}/download', [FileController::class, 'downloadFile']);
// Route::post('fileupload', [FileController::class, 'fileUpload']);
// Route::get('/files', [FileController::class, 'listFiles']);
// Route::get('/files/{id}', [FileController::class, 'downloadFile']);
