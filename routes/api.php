
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::middleware('auth:api')->get('/user', 'UserController@AuthRouteAPI');
Route::post('register','Api\AuthUserController@register');
Route::get('/get-role','User\UserController@getRole')->name('get-role');
Route::post('forget-password','Api\AuthUserController@forgetPassword');
Route::post('reset-password','Api\AuthUserController@resetPassword');
Route::post('login','Api\AuthUserController@login');
Route::middleware('auth:api')->group(function(){

    Route::name('user.')->prefix('user')->group(function () {
        Route::get('/user-list', 'User\UserController@index')->name('users');
        Route::get('/user-add', 'User\UserController@addUser')->name('user-add');
        Route::post('insert-user', 'User\UserController@insertUser')->name('insert-user');
        Route::post('update-user', 'User\UserController@updateUser')->name('update-user');
        Route::get('delete-user', 'User\UserController@deleteUser')->name('delete-user');
        Route::post('/update-password', 'User\UserController@updatePassword')->name('update-password');
        Route::get('/assign-role', 'User\UserController@assignRole')->name('assign-role');
        Route::post('update-assign-role', 'User\UserController@updateAssignRole')->name('update-assign-role');
        Route::get('/remove-role', 'User\UserController@removeRole')->name('remove-role');
        Route::get('/add-role', 'User\UserController@addRole')->name('add-role');
        // Route::get('/get-role', [UserController::class,'getRole'])->name('get-role');
        Route::get('/delete-role', 'User\UserController@deleteRole')->name('delete-role');
        Route::post('update-role', 'User\UserController@updateRole')->name('update-role');
        Route::post('insert-role', 'User\UserController@insertRole')->name('insert-role');
        Route::get('/assign-permission', 'User\UserController@assignPermission')->name('assign-permission');
        Route::get('/remove-permission', 'User\UserController@removePermission')->name('remove-permission');
        Route::post('update-assign-permission', 'User\UserController@updateAssignPermission')->name('update-assign-permission');
        Route::get('/add-permission', 'User\UserController@addPermission')->name('add-permission');
        Route::get('/get-permission', 'User\UserController@getPermission')->name('get-permission');
        Route::get('/delete-permission', 'User\UserController@deletePermission')->name('delete-permission');
        Route::post('update-permission', 'User\UserController@updatePermission')->name('update-permission');
        Route::post('insert-permission', 'User\UserController@insertPermission')->name('insert-permission');
        Route::post('update-user-status', 'User\UserController@updateUserStatus')->name('updateUserStatus');
    });

    Route::name('product.')->prefix('product')->group(function () {
        Route::get('/list',  'ProductController@index')->name('list');
        Route::get('/new-product',  'ProductController@create')->name('create');
        Route::post('/delete-product',  'ProductController@delete')->name('delete');
        Route::get('/trashed-products',  'ProductController@trashed')->name('trashed');
        Route::post('/destroy-product',  'ProductController@destroy')->name('destroy');
        Route::post('/restore-product',  'ProductController@restore')->name('restore');
        Route::post('/update-product',  'ProductController@update')->name('update');
        Route::post('/save-product',  'ProductController@save')->name('save');
        
    });

    Route::name('category.')->prefix('category')->group(function () {
        Route::get('/list','CategoryController@index' )->name('list');
        Route::post('/new-category', 'CategoryController@add')->name('add');
        Route::get('/delete-category','CategoryController@delete')->name('delete');
        Route::get('/trashed-category','CategoryController@trashed')->name('trashed');
        Route::get('/destroy-category','CategoryController@destroy')->name('destroy');
        Route::get('/restore-category','CategoryController@restore')->name('restore');
        Route::post('/update-category','CategoryController@update')->name('update');
        
    });
    Route::get('logout','AuthUserController@logout');
    
});
