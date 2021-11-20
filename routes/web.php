<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware([Authenticate::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/admins', [UserController::class, 'readAdmins']);
    Route::post('/admins', [UserController::class, 'createAdmin']);
    Route::get('/companies', [CompanyController::class, 'readCompanies']);
    Route::post('/companies', [CompanyController::class, 'createCompany']);
    Route::get('/companies/{company_id}', [CompanyController::class, 'readCompany']);
    Route::post('/companies/{company_id}/admins', [CompanyController::class, 'createCompanyAdmin']);
    Route::get('/companies/{company_id}/employees', [CompanyController::class, 'readCompanyEmployees']);
    Route::post('/companies/{company_id}/employees', [CompanyController::class, 'createCompanyEmployee']);
    Route::get('/employees', [UserController::class, 'readEmployees']);
    Route::post('/employees', [UserController::class, 'createEmployee']);
});