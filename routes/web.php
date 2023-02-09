<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\BackendUserController;
use App\Http\Controllers\BackendCategoryController;
use App\Http\Controllers\Product\BackendProductController;
use Illuminate\Support\Facades\Route;


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





    Route::name('user.')->prefix('user')->group(function () {
        Route::get('test', 'User\BackendUserController@index')->name('yest');
        Route::get('/user-list','User\BackendUserController@index')->name('users');
        Route::get('/user-add','User\BackendUserController@addUser')->name('user-add');
        Route::post('insert-user','User\BackendUserController@insertUser')->name('insert-user');
        Route::post('update-user','User\BackendUserController@updateUser' )->name('update-user');
        Route::get('delete-user','User\BackendUserController@deleteUser' )->name('delete-user');
        Route::get('trashed-users','User\BackendUserController@restoreUser')->name('restore-user');
        Route::get('restore-user','User\BackendUserController@trashedUsers')->name('trashed-users');
        Route::get('destroy-user','User\BackendUserController@destroyUser')->name('destroy-user');
        Route::get('change-password','User\BackendUserController@changePassword' )->name('change-password');
        Route::post('update-password','User\BackendUserController@updatePassword')->name('update-password');
        Route::get('change-password-own','User\BackendUserController@getChangePasswordByOwner')->name('change-password-own');
        Route::post('change-password-own','User\BackendUserController@postChangePasswordByOwner')->name('change-password-own');
        Route::get('/assign-role','User\BackendUserController@assignRole' )->name('assign-role');
        Route::post('update-assign-role','User\BackendUserController@updateAssignRole' )->name('update-assign-role');
        Route::get('/remove-role','User\BackendUserController@removeRole')->name('remove-role');
        Route::get('/add-role','User\BackendUserController@addRole' )->name('add-role');
        Route::get('/get-role','User\BackendUserController@getRole' )->name('get-role');
        Route::get('/delete-role','User\BackendUserController@deleteRole')->name('delete-role');
        Route::post('update-role','User\BackendUserController@updateRole')->name('update-role');
        Route::post('insert-role', 'User\BackendUserController@insertRole')->name('insert-role');
        Route::get('/assign-permission', 'User\BackendUserController@assignPermission')->name('assign-permission');
        Route::get('/remove-permission', 'User\BackendUserController@removePermission')->name('remove-permission');
        Route::post('update-assign-permission', 'User\BackendUserController@updateAssignPermission')->name('update-assign-permission');
        Route::get('/add-permission', 'User\BackendUserController@addPermission')->name('add-permission');
        Route::get('/get-permission', 'User\BackendUserController@getPermission')->name('get-permission');
        Route::get('/delete-permission', 'User\BackendUserController@deletePermission')->name('delete-permission');
        Route::post('update-permission', 'User\BackendUserController@updatePermission')->name('update-permission');
        Route::post('insert-permission','User\BackendUserController@insertPermission')->name('insert-permission');
        Route::get('update-user-status', 'User\BackendUserController@updateUserStatus')->name('updateUserStatus');

    });


    Route::name('product.')->prefix('product')->group(function () {
        Route::get('/list', [BackendProductController::class,'index'])->name('list');
        Route::post('/list', [BackendProductController::class,'index'])->name('list');
        Route::get('/new-product', [BackendProductController::class,'create'])->name('create');
        Route::get('/delete-product', [BackendProductController::class,'delete'])->name('delete');
        Route::get('/trashed-products', [BackendProductController::class,'trashed'])->name('trashed');
        Route::get('/destroy-product', [BackendProductController::class,'destroy'])->name('destroy');
        Route::get('/restore-product', [BackendProductController::class,'restore'])->name('restore');
        Route::post('/update-product', [BackendProductController::class,'update'])->name('update');
        Route::post('/save-product', [BackendProductController::class,'save'])->name('save');
        Route::get('/get-category', [BackendProductController::class,'getCategory'])->name('getcategory');
        Route::post('/get-tags', [BackendProductController::class,'getTags'])->name('gettags');
        
    });

    Route::name('category.')->prefix('category')->group(function () {
        Route::get('/list', [BackendCategoryController::class,'index'])->name('list');
        Route::get('/new-category', [BackendCategoryController::class,'create'])->name('create');
        Route::get('/delete-category', [BackendCategoryController::class,'delete'])->name('delete');
        Route::get('/trashed-category', [BackendCategoryController::class,'trashed'])->name('trashed');
        Route::get('/destroy-category', [BackendCategoryController::class,'destroy'])->name('destroy');
        Route::get('/restore-category', [BackendCategoryController::class,'restore'])->name('restore');
        Route::post('/update-category', [BackendCategoryController::class,'update'])->name('update');
        Route::post('/save-product', [BackendCategoryController::class,'save'])->name('save');
        
    });



