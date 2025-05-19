<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuditController;
use Illuminate\Support\Facades\Route;

/* No Authentification */

// User
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);

// Category
Route::get('/category', [CategoryController::class, 'listCategory']);
Route::get('/category/{id}', [CategoryController::class, 'getCategory']);

// Post
Route::get('/post', [PostController::class, 'listPost']);
Route::get('/post/{id}', [PostController::class, 'getPost']);

/* With JWT Authentification */
Route::middleware('jwt.auth')->group(function () {
    // User
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::get('/user/all', [AuthController::class, 'getAllUser']);
    Route::put('/user/update/{id}', [AuthController::class, 'updateUser']);
    Route::delete('/user/delete/{id}', [AuthController::class, 'deleteUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Category
    Route::post('/category/create', [CategoryController::class, 'createCategory']);
    Route::delete('/category/delete/{id}', [CategoryController::class, 'deleteCategory']);
    Route::put('/category/edit/{id}', [CategoryController::class, 'updateCategory']);

    // Post
    Route::post('/post/create', [PostController::class, 'createPost']);
    Route::put('/post/edit/{id}', [PostController::class, 'updatePost']);
    Route::delete('/post/delete/{id}', [PostController::class, 'deletePost']);

    // Audit
    Route::get('/audits/post', [AuditController::class, 'postAudits']);
    Route::get('/audits/category', [AuditController::class, 'categoryAudits']);
    Route::get('/audits/user', [AuditController::class, 'userAudits']);
});
