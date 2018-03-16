<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class SessionsController extends Controller
{
    public function create()
    {
      return view('sessions.create');
    }

    public function store(Request $request)
    {
      $credentials = $this->validate($request, [
        'email' => 'required|email|max:255',
        'password' => 'required'
      ]);

      if (Auth::attempt($credentials, $request->has('remember'))) {
        /*
        Auth::attempt() 方法可接收两个参数，第一个参数为需要进行用户身份认证的数组，第二个参数为是否为用户开启『记住我』功能的布尔值。
        */

        session()->flash('success', '欢迎回来！');
        return redirect()->route('users.show', [Auth::user()]);//Laravel 提供的 Auth::user() 方法来获取 当前登录用户 的信息，并将数据传送给路由。
      } else {
        session()->flash('danger', '很抱歉，您的邮箱和密码不匹配！');
        return redirect()->back();
      }
      /*
      借助 Laravel 提供的 Auth 的 attempt 方法可以很方便的完成用户的身份认证操作，如下所示：attempt 方法会接收一个数组来作为第一个参数，该参数提供的值将用于寻找数据库中的用户数据。attempt 方法执行的代码逻辑如下：
      使用 email 字段的值在数据库中查找； 如果用户被找到：
      1). 先将传参的 password 值进行哈希加密，然后与数据库中 password 字段中已加密的密码进行匹配；
      2). 如果匹配后两个值完全一致，会创建一个『会话』给通过认证的用户。会话在创建的同时，也会种下一个名为 laravel_session 的 HTTP Cookie，以此 Cookie 来记录用户登录状态，最终返回 true；
      3). 如果匹配后两个值不一致，则返回 false；
      如果用户未找到，则返回 false。使用 Auth 前需要对其进行引用。
      */
    }

    public function destroy()
    {
      Auth::logout();//调用 Laravel 默认提供的 Auth::logout() 方法来实现用户的退出功能
      session()->flash('success', '您已退出！');
      return redirect('login');
    }
}
