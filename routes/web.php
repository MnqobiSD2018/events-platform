<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeRedirectController;
use App\Http\Controllers\EmployeeHomeController;
use App\Http\Controllers\EmployeeAnnouncementController;
use App\Http\Controllers\EmployeeActivityController;
use App\Http\Controllers\EmployeeTrackerIntegrationController;
use App\Http\Middleware\EnsureUserType;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Routing\Middleware\ValidateSignature;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkin/{participant}', [CheckinController::class, 'scan'])->middleware(ValidateSignature::class)->name('checkin.scan');

Route::middleware([Authenticate::class, EnsureEmailIsVerified::class])->group(function () {
    Route::get('/home', HomeRedirectController::class)->name('home');

    Route::middleware(EnsureUserType::class.':company_admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', EventController::class);
        Route::post('/events/{event}/import-csv', [EventController::class, 'importCsv'])->name('events.import.csv');
        Route::get('/events/{event}/report', [EventController::class, 'report'])->name('events.report');
        Route::get('/scanner', [CheckinController::class, 'scanner'])->name('checkin.scanner');
    });

    Route::prefix('employee')->name('employee.')->middleware(EnsureUserType::class.':employee')->group(function () {
        Route::get('/home', [EmployeeHomeController::class, 'index'])->name('home');
        Route::get('/announcements', [EmployeeAnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements/{announcement}/read', [EmployeeAnnouncementController::class, 'markAsRead'])->name('announcements.read');
        Route::get('/activities', [EmployeeActivityController::class, 'index'])->name('activities.index');
        Route::post('/activities', [EmployeeActivityController::class, 'store'])->name('activities.store');
        Route::get('/integrations', [EmployeeTrackerIntegrationController::class, 'index'])->name('integrations.index');
        Route::post('/integrations/{provider}/connect', [EmployeeTrackerIntegrationController::class, 'connect'])->name('integrations.connect');
        Route::post('/integrations/import', [EmployeeTrackerIntegrationController::class, 'import'])->name('integrations.import');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
