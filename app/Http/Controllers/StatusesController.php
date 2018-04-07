<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');//些需要用户登录之后才能执行的请求需要通过中间件来过滤,借助 Auth 中间件来为这两个动作添加过滤请求
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'content' => 'required|max:140'
      ]);

      Auth::user()->statuses()->create([
        'content' => $request['content']
      ]);
      /*
      通过$user->statuses()->create()方式来进行创建。这样会自动与用户进行关联。
      借助 Laravel 提供的 Auth::user() 方法我们可以获取到当前用户实例
      */
      return redirect()->back();
    }

    public function destroy(Status $status)
    {
      $this->authorize('destroy', $status);//做删除授权的检测，不通过会抛出 403 异常
      $status->delete();//调用 Eloquent 模型的 delete 方法进行删除
      session()->flash('success', '微博已删除！');
      return redirect()->back();//删除成功之后，将返回到执行删除微博操作的页面上。
    }


}
