<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\HomeHeroBannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\TimeslotController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\AlergenController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\ReportController;
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

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'getUser']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::apiResource('homeherobanners', HomeHeroBannerController::class);
    Route::apiResource('categories', CategoryController::class)->except('show');
    Route::get('/categories/tree', [CategoryController::class, 'getAsTree']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::post('/timeslots', [TimeslotController::class, 'store']);
    Route::apiResource('abouts', AboutController::class);
    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('authors', AuthorController::class)->except('show');
    Route::get('/authors/tree', [AuthorController::class, 'getAsTree']);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::get('/countries', [CustomerController::class, 'countries']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/statuses', [OrderController::class, 'getStatuses']);
    Route::post('orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']);
    Route::get('orders/{order}', [OrderController::class, 'view']);

    // Dashboard Routes
    Route::get('/dashboard/home-hero-banner-count', [DashboardController::class, 'activeHomeHeroBanners']);
    Route::get('/dashboard/category-count', [DashboardController::class, 'activeCategories']);
    Route::get('/dashboard/products-count', [DashboardController::class, 'activeProducts']);
    Route::get('/dashboard/articles-count', [DashboardController::class, 'activeArticles']);
    Route::get('/dashboard/authors-count', [DashboardController::class, 'activeAuthors']);
    Route::get('/dashboard/clients-count', [DashboardController::class, 'activeClients']);
    Route::get('/dashboard/latest-clients', [DashboardController::class, 'latestClients']);

    // Report routes
    Route::get('/report/orders', [ReportController::class, 'orders']);
    Route::get('/report/customers', [ReportController::class, 'customers']);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);
Route::get('/categories/{category:slug}/products', [ProductController::class, 'productsByCategory']);
Route::get('categories/{category}/products', [ProductController::class, 'productsByCategory'])->name('category.products');
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);