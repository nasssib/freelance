<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MassagesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\UsersController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('admin')->group(function () {

        ////admin
        Route::get('/make/service/accepted/{id}', [AdminController::class, 'makeServiceaccept']);
        Route::get('/make/project/accepted/{id}', [AdminController::class, 'makeProjectaccept']);
        Route::get('/services/unaccepted', [AdminController::class, 'unaccepted_services']);
        Route::get('/projects/unaccepted', [AdminController::class, 'unaccepted_projects']);
        ///category
        Route::post('/category/store', [CategoriesController::class, 'store']);
        Route::delete('/category/delete/{id}', [CategoriesController::class, 'destroy']);
        Route::post('/category/update/{id}', [CategoriesController::class, 'update']);
        ////
        Route::get('/unaccepted/service/show/{id}', [ServicesController::class, 'show_un_accepted']);
        /////
        Route::get('/unaccepted/project/show/{id}', [ProjectController::class, 'show_un_accepted']);
    });

    Route::middleware('freelancer')->group(function () {

        ///service
        Route::post('/service/store', [ServicesController::class, 'store']);
        Route::post('/service/update', [ServicesController::class, 'update']);
        Route::delete('/service/delete/{id}', [ServicesController::class, 'destroy']);
        Route::get('/unaccepted/service/show/{id}', [ServicesController::class, 'show_un_accepted']);
    });

    Route::middleware('user')->group(function () {

        ///rate
        Route::post('/rating', [RatesController::class, 'rating']);
        ///project
        Route::post('/project/store', [ProjectController::class, 'store']);
        Route::post('/project/update', [ProjectController::class, 'update']);
        Route::delete('/project/delete/{id}', [ProjectController::class, 'destroy']);
        Route::get('/unaccepted/project/show/{id}', [ProjectController::class, 'show_un_accepted']);
    });

    Route::get('/auth/logout', [UsersController::class, 'logout']);


    Route::post('/massage/send', [MassagesController::class, 'store']);
    Route::get('/massage/user', [MassagesController::class, 'index']);
    Route::delete('/massage/delete/{id}', [MassagesController::class, 'destroy']);

    ////
    Route::post('/profile/update', [ProfilesController::class, 'update']);
});
Route::post('/auth/register', [UsersController::class, 'createUser']);
Route::post('/auth/login', [UsersController::class, 'login']);

/////

Route::get('/category/index', [CategoriesController::class, 'index']);
Route::get('/category/show/{id}', [CategoriesController::class, 'show']);

/////
Route::get('/profile/show/{id}', [ProfilesController::class, 'show']);

/////
Route::get('/accepted/service/show/{id}', [ServicesController::class, 'show_accepted']);

/////
Route::get('/accepted/project/show/{id}', [ProjectController::class, 'show_accepted']);

////
Route::get('/search/{search}', [UsersController::class, 'search']);
Route::get('/search/user/{search}', [UsersController::class, 'searchForuser']);
