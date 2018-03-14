<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');
/*
get 方法传递了两个参数，第一个参数指明了 URL，第二个参数指明了处理该 URL 的控制器动作。get 表明这个路由将会响应 GET 请求，并将请求映射到指定的控制器动作上。比方说，我们向 http://sample.test/ 发出了一个请求，则该请求将会由 StaticPagesController 的 home 方法进行处理。
通过在路由后面链式调用 name 方法来为路由指定名称
*/

Route::get('signup', 'UsersController@create')->name('signup');
/*signup 和 /signup 从使用上来看，并无区别，Laravel 框架兼容这两种写法*/
