<?php

use Illuminate\Http\Request;
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



//Route::post('/register', 'ApiAuthController@register');

//Route::get('/allow_register', 'AdminsController@allow_register');
//Route::post('/login', 'ApiAuthController@checking');

use App\Http\Middleware\EnsureAdminConfirmation;

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
Route::post('/login', 'AuthController@login');
Route::post('/register', 'ApiAuthController@register');


Route::middleware(['assign.guard'])->group(function(){
    Route::get('/logout', 'ApiAuthController@logout');
});

Route::middleware(['assign.guard:user'])->group(function(){
   Route::get('/user','UserController@index');
   Route::middleware(EnsureAdminConfirmation::class)->group(function(){
       Route::get('/verified','UserController@verifies');
       Route::post('/new-worker', 'WorkerController@new_worker');
       Route::get('/worker-list', 'WorkerController@worker_list');
       Route::post('/make-model', 'ModelController@make_model');
       Route::get('/model-list', 'ModelController@model_list');
       Route::get('/get-worker/{worker_id}', 'WorkerController@get_worker_info');
       Route::get('/get-model/{model_boot_id}', 'ModelController@get_model_info');
       Route::get('/get-sizes-by-model-boot/{model_boot_id}', 'ModelController@get_sizes_by_model_boot');
       Route::post('/make-order/{worker_id}', 'ModelController@make_order');
       Route::get('/model-worker-satisfy/{worker_id}','ModelController@model_from_worker_at_least_one_size');
       Route::get('/size-worker-satisfy/{worker_id}/{model_id}','ModelController@size_worker_from_model_satisfy');
       Route::post('/complete-order','ModelController@complete_order');
       Route::get('/models-worker-satisfy-order/{worker_id}','ModelController@models_boot_worker_at_least_one_order');
       Route::get('/orders-by-worker/{worker_id}','ModelController@orders_by_worker');
       Route::get('/orders-by-model-boot-worker/{model_boot_worker_id}','ModelController@orders_by_model_boot_worker');
       Route::get('/models-boot-satisfy-worker/{worker_id}','ModelController@models_boot_with_at_least_one_size_worker');
       Route::get('/size-worker-satisfy-worker/{worker_id}','ModelController@size_worker_by_worker');
       Route::get('/size-worker-satisfy-worker-model/{worker_id}/{model_id}','ModelController@size_worker_by_worker_and_model');
       
   });
   
});


Route::middleware(['assign.guard:admin'])->group(function(){
   Route::get('/admin','AdminsController@index');
   Route::get('/admin/allow_register/{allow_register}','AdminsController@allow_register');
   Route::get('/admin/users-not-verified','AdminsController@get_users_not_verified');
   Route::get('/admin/accept-email/{user_id}','AdminsController@accept_email');
   Route::get('/admin/deny-email/{user_id}','AdminsController@deny_email');
   
});
