<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BlogController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['web'])->prefix('/')->name('')->group(function () {
    // Parent Route Group
    Route::prefix('parent/')->name('parent.')->group(function () {
        // Category Route        
        Route::get('category/trash', [CategoryController::class, 'trash'])->name('category.trash');
        Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
        Route::delete('category/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('category.force_delete');
        Route::resource('category', CategoryController::class)->names('category');
    }); 

    // Author Route
    Route::get('author/trash', [AuthorController::class, 'trash'])->name('author.trash');
    Route::post('author/{id}/restore', [AuthorController::class, 'restore'])->name('author.restore');
    Route::delete('author/{id}/force-delete', [AuthorController::class, 'forceDelete'])->name('author.force_delete');
    Route::resource('author', AuthorController::class)->names('author');

    // Blog Route
    Route::get('blog/trash', [BlogController::class, 'trash'])->name('blog.trash');
    Route::post('blog/{id}/restore', [BlogController::class, 'restore'])->name('blog.restore');
    Route::delete('blog/{id}/force-delete', [BlogController::class, 'forceDelete'])->name('blog.force_delete');
    Route::post('blog/upload', [BlogController::class, 'upload'])->name('blog.upload');
    Route::resource('blog', BlogController::class)->names('blog');
});
