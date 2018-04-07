<?php

namespace App\Http\Controllers;
/*
namespace 代表的是 命名空间,开发者可以利用命名空间来区分归类不同的代码功能，避免引起变量名或函数名的冲突。你可以把命名空间理解为文件路径，把变量名理解为文件。
*/

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Status;
use Auth;
/*
用 use 来引用在 PHP 文件中要使用的类，引用之后便可以对其进行调用。
*/

class StaticPagesController extends Controller
//定义了一个 StaticPagesController 类，这个类继承了父类 App\Http\Controllers\Controller，这意味着可以在 StaticPagesController 类中任意使用父类中除私密方法外的其它方法。
{
    public function home()//定义了类的home方法
    {
      $feed_items = [];
      if (Auth::check()) {
        $feed_items = Auth::user()->feed()->paginate(30);
      }
      /*
      定义了一个空数组 feed_items 来保存微博动态数据。由于用户在访问首页时，可能存在登录或未登录两种状态，因此我们需要确保当前用户已进行登录时才从数据库读取数据。前面章节我们已讲过，可以使用 Auth::check() 来检查用户是否已登录。另外我们还对微博做了分页处理的操作，每页只显示 30 条微博。
      */
      return view('static_pages/home', compact('feed_items'));
      /*
      使用到 view 方法在控制器中指定渲染某个视图。view 方法接收两个参数，第一个参数是视图的路径名称，第二个参数是与视图绑定的数据，第二个参数为可选参数。将会渲染在 resources/views 文件夹下的 static_pages/home.blade.php 文件。默认情况下，所有的视图文件都存放在 resources/views 文件夹下。
      */
    }

    public function help()
    {
      return view('static_pages/help');
    }

    public function about()
    {
      return view('static_pages/about');
    }
}
