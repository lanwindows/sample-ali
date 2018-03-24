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

Route::resource('users', 'UsersController');
/*
resource 方法将遵从 RESTful 架构为用户资源生成路由。该方法接收两个参数，第一个参数为资源名称，第二个参数为控制器名称。
上面代码将等同于：
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
*/

Route::get('login', 'SessionsController@create')->name('login');//显示登录页面
Route::post('login', 'SessionsController@store')->name('login');//创建新会话（登录）
Route::delete('logout', 'SessionsController@destroy')->name('logout');//销毁会话（退出登录）

Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email'); //邮件激活帐号

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');//显示重置密码的邮箱发送页面
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');//邮箱发送重设链接
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');//密码更新页面
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');//执行密码更新操作
