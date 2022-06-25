<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VesselController;
use App\Models\VesselsType;
use App\Models\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebNotificationController;
use App\Notifications\SendNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

Route::resource('/login', LoginController::class, [
    'names' => [
        'index' => 'login',
    ],
]);

Route::middleware(['auth'])->group(function () {
# Web Routes
Route::get('/', [HomeController::class, 'index'])->name('/');

Route::get('/admin', function () {
    $types = Auth::all();
    return view('auth.admin', [
        'types' => $types,
    ]);
})->name('admin')->middleware('type:admin');

Route::get('/management', function () {
    $types = VesselsType::all();
    $users = User::where('type', '1')->get();
    Notification::send($users, new SendNotifications('تم الدخول على  متابعة السفن', auth()->user()->id));
    
    return view('vessels.management', [
        'types' => $types,
    ]);
})->name('management')->middleware('type:admin,vessel');

Route::get('/archive', function () {
    $types = VesselsType::all();
    $users = User::where('type', '1')->get();
    Notification::send($users, new SendNotifications('تم الدخول على  أرشيف السفن', auth()->user()->id));
    return view('vessels.archive', [
        'types' => $types,
    ]);
})->name('archive')->middleware(['auth','canView:archive']);

Route::get('/vessels/end/{id}', [VesselController::class, 'end'])->middleware('type:admin,vessel');

//datatable
Route::get('/Duser', [DatatableController::class, 'users'])->name('Duser');
Route::get('/Dmanagement', [DatatableController::class, 'management'])->name('Dmanagement');
Route::get('/Darchive', [DatatableController::class, 'archive'])->name('Darchive');
Route::get('/DLoading/{id}', [DatatableController::class, 'loading'])->name('DLoading');
Route::get('/DArrival/{id}', [DatatableController::class, 'arrival'])->name('DArrival');
Route::get('/DDirect/{id}', [DatatableController::class, 'direct'])->name('DDirect');
Route::get('/DStops/{id}', [DatatableController::class, 'stops'])->name('DStops');
Route::get('/DMinus/{id}', [DatatableController::class, 'minus'])->name('DMinus');
Route::get('/DTravels/{id}', [DatatableController::class, 'travels'])->name('DTravels');
Route::get('/DCars/{id}', [DatatableController::class, 'cars'])->name('DCars');
Route::get('/DLive', [DatatableController::class, 'live'])->name('DLive');
Route::get('/DStats/{id}', [ReportController::class, 'DStats'])->name('DStats');


Route::get('/Rcars/{id}', [ReportController::class, 'cars'])->name('Rcars')->middleware('canView:cars');
Route::get('/DAnalysis/{id}', [ReportController::class, 'analysis'])->name('DAnalysis')->middleware('canView:analysis');
Route::get('/RStats/{id}', [ReportController::class, 'stats'])->name('RStats')->middleware('canView:stats');
Route::get('/RLoading/{id}', [ReportController::class, 'loading'])->name('RLoading')->middleware('canView:loading');
Route::get('/RArrival/{id}', [ReportController::class, 'arrival'])->name('RArrival')->middleware('canView:arrival');
Route::get('/RDirect/{id}', [ReportController::class, 'direct'])->name('RDirect')->middleware('canView:direct');
Route::get('/RQuantity/{id}', [ReportController::class, 'quantity'])->name('RQuantity')->middleware('canView:quantity');
Route::get('/RStops/{id}', [ReportController::class, 'stops'])->name('RStops')->middleware('canView:stops');
Route::get('/RMinus/{id}', [ReportController::class, 'minus'])->name('RMinus')->middleware('canView:minus');
Route::get('/RTravels/{id}', [ReportController::class, 'travels'])->name('RTravels')->middleware('canView:travels');
Route::get('/RLive', [ReportController::class, 'live'])->name('RLive');
Route::get('/carsAnalysis/{id}', [ReportController::class, 'carsAnalysis'])->name('carsAnalysis')->middleware('canView:cars_analysis');
Route::get('/carAnalysis/{car_no}/{id}', [ReportController::class, 'carAnalysis'])->name('carAnalysis')->middleware('canView:cars_analysis');
Route::get('/carOwners/{id}', [ReportController::class, 'carOwners'])->name('carOwners')->middleware('canView:car_owners');
Route::get('/carOwner/{carOwner}/{vessel_id}', [ReportController::class, 'carOwner'])->name('carOwner')->middleware('canView:car_owners');

Route::post('/roles', [UserController::class, 'roles'])->name('roles')->middleware('type:admin');

Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications')->middleware('type:admin');

Route::resource('cars', CarController::class);
Route::resource('users', UserController::class)->middleware('type:admin');

Route::resource('/vessels', VesselController::class);


Route::resource('/register', RegisterController::class)->middleware('type:admin');

Route::get('/logout', LogoutController::class);

Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::get('/firebase', function () {return view('firebase');})->name('firebase');


Route::get('/push-notificaiton', [WebNotificationController::class, 'index'])->name('push-notificaiton');
Route::post('/store-token', [WebNotificationController::class, 'storeToken'])->name('store.token');
Route::post('/send-web-notification', [WebNotificationController::class, 'sendWebNotification'])->name('send.web-notification');