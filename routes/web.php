<?php

use App\Domains\Complaint\Models\Complaint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/which', function () {
    return response()->json([
        'instance' => env('APP_INSTANCE', 'unknown'),
        'time' => now()->toDateTimeString()
    ]);
});
Route::get('/login-test', function () {
    session(['student' => 'Fatima']); // أي قيمة تجريبية
    return response()->json(['status' => 'logged', 'instance' => env('APP_INSTANCE')]);
});


Route::get('/profile', function () {
    return response()->json([
        'instance' => env('APP_INSTANCE', 'unknown'),
        'session_student' => session('student', 'not-logged'),
        'cookies' => request()->cookies->all()
    ]);
});

Route::get('/cache-test', function () {
    $key = 'test.complaints.count';
    $val = Cache::remember($key, 600, function () {
        return Complaint::count();
    });
    return response()->json([
        'instance' => env('APP_INSTANCE', 'unknown'),
        'cached_value' => $val,
        'cache_key' => $key
    ]);
});


Route::get('/cache-forget', function () {
    $key = 'test.complaints.count';
    Cache::forget($key);
    return response()->json(['status' => 'forgotten', 'cache_key' => $key]);
});
