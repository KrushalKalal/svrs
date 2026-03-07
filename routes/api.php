<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\MeetingController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', fn(Request $request) => $request->user());

    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/dashboard-lead/{type}', [DashboardController::class, 'dashboard_lead']);

    Route::get('/notifications', [DashboardController::class, 'notifications']);
    Route::post('/notifications-read', [DashboardController::class, 'notifications_read']);
    
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('leads', [LeadController::class, 'leadListApi']);
    Route::get('create-lead', [LeadController::class, 'createLeadApi']);
    Route::post('store-lead', [LeadController::class, 'StoreLeadApi']);

    Route::get('create-meeting/{id}', [MeetingController::class, 'createMeetingApi']);
    Route::post('store-meeting', [MeetingController::class, 'storeMeetingApi']);
    Route::delete('delete-meeting/{id}', [MeetingController::class, 'deleteMeetingApi']);
    Route::get('get-meeting-reasons/{id}', [MeetingController::class, 'getMeetingReasons']);
    Route::get('lead-meeting/{id}', [MeetingController::class, 'leadMeeting']);
});
