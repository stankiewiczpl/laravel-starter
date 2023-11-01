<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Routing\Router;
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
Route::group(['prefix' => 'auth'], function (Router $router){
    $router->post('login', LoginController::class)->name('api.auth.login');
    $router->post('register', RegisterController::class)->name('api.auth.register');
    $router->post('forgot-password', ForgotPasswordController::class)->name('api.auth.forgot-password');
    $router->post('reset-password', ResetPasswordController::class)->name('api.auth.reset-password');
    $router->group(['middleware' => 'auth:api'], function (Router $router){
        $router->get('user', UserController::class)->name('api.auth.user');
        $router->post('logout', LogoutController::class)->name('api.auth.logout');
    });
});
