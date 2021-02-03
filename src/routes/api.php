<?php

use App\Consts\Routing;
use App\Http\Controllers\AlarmsController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NotificationsTypeController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TasksStatusesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/refreshToken', [UserController::class, 'refreshToken']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'notes'], function () {
        Route::get('/', [NoteController::class, Routing::GET_ALL]);
        Route::get('/{id}', [NoteController::class, Routing::GET_ONE]);
        Route::post('/validate', [NoteController::class, 'validateData']);
        Route::post('/', [NoteController::class, Routing::CREATE]);
        Route::delete('/{id}', [NoteController::class, Routing::DELETE]);
        Route::put('/{id}', [NoteController::class, Routing::UPDATE]);
    });

    Route::group(['prefix' => 'bookmarks'], function () {
        Route::get('/', [BookmarkController::class, Routing::GET_ALL]);
        Route::post('/validateData', [BookmarkController::class, 'validateData']);
        Route::post('/validateDetails', [BookmarkController::class, 'validateDetails']);
        Route::post('/getIcon', [BookmarkController::class, 'getIcon']);
        Route::get('/{id}', [BookmarkController::class, Routing::GET_ONE]);
        Route::post('/', [BookmarkController::class, Routing::CREATE]);
        Route::delete('/{id}', [BookmarkController::class, Routing::DELETE]);
        Route::put('/{id}', [BookmarkController::class, Routing::UPDATE]);
    });

    Route::group(['prefix' => 'alarms'], function () {
        Route::get('/', [AlarmsController::class, Routing::GET_ALL]);
        Route::post('/validate/single', [AlarmsController::class, 'validateSingleAlarm']);
        Route::post('/validate/periodic', [AlarmsController::class, 'validatePeriodicAlarm']);
        Route::post('/create/single', [AlarmsController::class, 'createSingle']);
        Route::post('/create/periodic', [AlarmsController::class, 'createPeriodic']);
        Route::delete('/{id}/single', [AlarmsController::class, 'deleteSingleAlarm']);
        Route::delete('/{id}/periodic', [AlarmsController::class, 'deletePeriodicAlarm']);
        Route::post('/{id}/check', [AlarmsController::class, 'checkAlarm']);
        Route::post('/{id}/uncheck', [AlarmsController::class, 'uncheckAlarm']);
        Route::group(['prefix' => 'notifications'], function () {
            Route::delete('/{id}', [AlarmsController::class, 'deleteNotification']);
            Route::post('/{id}/check', [AlarmsController::class, 'checkNotification']);
            Route::post('/{id}/uncheck', [AlarmsController::class, 'uncheckNotification']);
            Route::group(['prefix' => 'types'], function () {
                Route::get('/', [NotificationsTypeController::class, Routing::GET_ALL]);
            });
        });
    });

    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/', [TasksController::class, Routing::GET_ALL]);
        Route::post('/validate/single', [TasksController::class, 'validateSingleTask']);
        Route::post('/validate/periodic', [TasksController::class, 'validatePeriodicTask']);
        Route::post('/create/single', [TasksController::class, 'createSingle']);
        Route::post('/create/periodic', [TasksController::class, 'createPeriodic']);
        Route::post('/{id}/change-status', [TasksController::class, 'changeStatus']);
        Route::delete('/{id}/single', [TasksController::class, 'deleteSingle']);
        Route::delete('/{id}/periodic', [TasksController::class, 'deletePeriodic']);
        Route::group(['prefix' => 'statuses'], function () {
            Route::get('/', [TasksStatusesController::class, Routing::GET_ALL]);
        });
    });
});
