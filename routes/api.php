<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DiseaseDetectionController;
use App\Http\Controllers\Api\TanamanController;
use Illuminate\Support\Facades\Http;



Route::post('/sensor', [SensorController::class, 'store']);
    

Route::post('/login', [AuthController::class, 'login']);
Route::get('/batas', [SensorController::class, 'getBatas']);
Route::post('/update-batas', [SensorController::class, 'updateBatas']);
Route::get('/tanaman/aktif', [SensorController::class, 'getTanamanAktif']);
Route::get('/tanaman', [TanamanController::class, 'index']);
Route::get('/tanaman/{tanaman}', [TanamanController::class, 'show'])->whereNumber('tanaman');
Route::middleware(['auth:sanctum', 'abilities:plants:manage'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/tanaman', [TanamanController::class, 'store']);
    Route::put('/tanaman/{tanaman}', [TanamanController::class, 'update'])->whereNumber('tanaman');
    Route::patch('/tanaman/{tanaman}', [TanamanController::class, 'update'])->whereNumber('tanaman');
    Route::delete('/tanaman/{tanaman}', [TanamanController::class, 'destroy'])->whereNumber('tanaman');
});
Route::get('/sensor/export', [SensorController::class, 'export']);
Route::get('/sensor/filter', [SensorController::class, 'filterData']);

// Deteksi penyakit daun dari aplikasi Android
Route::get('/sensor/latest', [DiseaseDetectionController::class, 'latestSensor']);
Route::post('/disease-detection', [DiseaseDetectionController::class, 'store']);
Route::get('/disease-detections', [DiseaseDetectionController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/proxy-predict', function (\Illuminate\Http\Request $request) {
    $data = $request->all();

    try {
        $response = Http::post('http://127.0.0.1:5000/predict', $data);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Server Python tidak merespon dengan benar',
                'status_code' => $response->status()
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
