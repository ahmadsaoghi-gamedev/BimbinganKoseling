<?php

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

// API route for getting student data
Route::get('/siswa/{id}', function ($id) {
    $siswa = \App\Models\Siswa::find($id);
    
    if (!$siswa) {
        return response()->json([
            'success' => false,
            'message' => 'Siswa tidak ditemukan'
        ], 404);
    }
    
    return response()->json([
        'success' => true,
        'data' => $siswa
    ]);
});
