<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\FlagController as AdminFlagController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\AttendanceController as GuruAttendanceController;
use App\Http\Controllers\Guru\PermissionController as GuruPermissionController;
use App\Http\Controllers\Guru\FlagController as GuruFlagController;
use App\Http\Controllers\Ortu\DashboardController as OrtuDashboardController;
use App\Http\Controllers\Ortu\PermissionController as OrtuPermissionController;
use App\Http\Controllers\Ortu\FlagController as OrtuFlagController;
use App\Http\Controllers\Ortu\AttendanceController as OrtuAttendanceController;
use App\Http\Controllers\Ortu\ChartController as OrtuChartController;
use App\Http\Controllers\Ortu\BarcodeController as OrtuBarcodeController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\OrtuController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\HolidayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Debug route
Route::get('/debug/user', function() {
    if (auth()->check()) {
        return response()->json([
            'user' => auth()->user(),
            'role' => auth()->user()->role,
            'session' => session()->all()
        ]);
    }
    return response()->json(['message' => 'Not authenticated']);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Student Routes
        Route::resource('students', StudentController::class);
        Route::post('students/{id}/generate-barcode', [StudentController::class, 'generateBarcode'])->name('students.generate-barcode');
        
        // Permission Routes
        Route::resource('permissions', AdminPermissionController::class);
        Route::put('permissions/{id}/approve', [AdminPermissionController::class, 'approve'])->name('permissions.approve');
        Route::put('permissions/{id}/reject', [AdminPermissionController::class, 'reject'])->name('permissions.reject');
        
        // Flag Routes
        Route::resource('flags', AdminFlagController::class);
        Route::put('flags/{id}/resolve', [AdminFlagController::class, 'resolve'])->name('flags.resolve');
        
        // Guru Routes
        Route::resource('gurus', GuruController::class);
        
        // Class Schedule Routes
        Route::resource('schedules', ClassScheduleController::class);
        
        // Ortu Routes
        Route::resource('ortus', OrtuController::class);
        
        // Report Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::post('/generate', [ReportController::class, 'generatePDF'])->name('generate');
            Route::post('/preview', [ReportController::class, 'previewPDF'])->name('preview');
        });
        
        // Holiday Routes
        Route::resource('holidays', HolidayController::class);
        
        // Tambahkan route admin lainnya di sini
    });

    // Guru Routes
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        
        // Attendance Routes
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [GuruAttendanceController::class, 'today'])->name('index'); // Redirect to today's attendance
            Route::get('/scanner', [GuruAttendanceController::class, 'index'])->name('scanner');
            Route::post('/scan', [GuruAttendanceController::class, 'scan'])->name('scan');
            Route::get('/today', [GuruAttendanceController::class, 'today'])->name('today');
            Route::get('/belum-pulang', [GuruAttendanceController::class, 'belumPulang'])->name('belum-pulang');
            Route::get('/manual', [GuruAttendanceController::class, 'manual'])->name('manual');
            Route::post('/manual', [GuruAttendanceController::class, 'storeManual'])->name('store-manual');
        });
        
        // Permission Routes (Guru)
        Route::resource('permissions', GuruPermissionController::class)->only(['index', 'show', 'update']);
        Route::put('permissions/{id}/approve', [GuruPermissionController::class, 'approve'])->name('permissions.approve');
        Route::put('permissions/{id}/reject', [GuruPermissionController::class, 'reject'])->name('permissions.reject');
        
        // Flag Routes (Guru)
        Route::resource('flags', GuruFlagController::class)->only(['index', 'show', 'update']);
        Route::put('flags/{id}/resolve', [GuruFlagController::class, 'resolve'])->name('flags.resolve');
    });

    // Ortu Routes
    Route::middleware(['role:ortu'])->prefix('ortu')->name('ortu.')->group(function () {
        Route::get('/dashboard', [OrtuDashboardController::class, 'index'])->name('dashboard');
        
        // Permission Routes (Ortu)
        Route::resource('permissions', OrtuPermissionController::class)->except(['edit', 'update']);
        Route::get('permissions/create/{student_id?}', [OrtuPermissionController::class, 'create'])->name('permissions.create-with-student');
        
        // Flag Routes (Ortu)
        Route::resource('flags', OrtuFlagController::class)->except(['edit', 'update']);
        Route::post('flags/create/{student_id}', [OrtuFlagController::class, 'createFlag'])->name('flags.create-for-student');
        
        // Attendance Routes (Ortu)
        Route::resource('attendance', OrtuAttendanceController::class)->only(['index', 'show']);
        Route::get('attendance/monthly-data', [OrtuAttendanceController::class, 'monthlyData'])->name('attendance.monthly-data');
        
        // Chart Routes (Ortu)
        Route::get('charts', [OrtuChartController::class, 'index'])->name('charts.index');
        Route::get('charts/data', [OrtuChartController::class, 'getChartData'])->name('charts.data');
        
        // Barcode Routes (Ortu)
        Route::prefix('barcode')->name('barcode.')->group(function () {
            Route::get('/', [OrtuBarcodeController::class, 'index'])->name('index');
            Route::get('/download/{id}', [OrtuBarcodeController::class, 'download'])->name('download');
            Route::get('/print/{id}', [OrtuBarcodeController::class, 'print'])->name('print');
        });
        
        // Tambahkan route ortu lainnya di sini
    });
});
