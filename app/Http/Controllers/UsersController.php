<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class UsersController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth', [
        'except' => ['show', 'create', 'store', 'index']
      ]);

      $this->middleware('guest', [
        'only' => ['create'] //只让未登录用户访问注册页面
      ]);
    }
    /*
    __construct 是 PHP 的构造器方法，当一个类对象被创建之前该方法将会被调用。我们在 __construct 方法中调用了 middleware 方法，该方法接收两个参数，第一个为中间件的名称，第二个为要进行过滤的动作。我们通过 except 方法来设定 指定动作 不使用 Auth 中间件进行过滤，意为 —— 除了此处指定的动作以外，所有其他动作都必须登录用户才能访问，类似于黑名单的过滤机制。相反的还有 only 白名单方法，将只过滤指定动作。我们提倡在控制器 Auth 中间件使用中，首选 except 方法，这样的话，当你新增一个控制器方法时，默认是安全的，此为最佳实践。
    */

    public function index()
    {
      $users = User::paginate(10);//使用 paginate 方法来指定每页生成的数据数量为 10 条
      return view('users.index', compact('users'));
    }

    public function create()
    {
      return view('users.create');
    }

    public function show(User $user)
    /*
    show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名 $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。
    */
    {
      return view('users.show', compact('user'));
      /*将用户对象 $user 通过 compact 方法转化为一个关联数组，并作为第二个参数传递给 view 方法，将数据与视图进行绑定*/
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|max:50',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|confirmed|min:6'
      ]);
      /*
      validator 由 App\Http\Controllers\Controller 类中的 ValidatesRequests 进行定义，因此可以在所有的控制器中使用 validate 方法来进行数据验证。validate 方法接收两个参数，第一个参数为用户的输入数据，第二个参数为该输入数据的验证规则.
      使用 required 来验证用户名是否为空,需要同时验证多个条件时，则可使用 | 对验证规则进行分割;
      使用 min 和 max 来限制用户名所填写的最小长度和最大长度;
      只需简单的使用 email 便能够完成邮箱格式的验证;
      'unique:users',使用唯一性验证，这里是针对于数据表 users 做验证;
      使用 confirmed 来进行密码匹配验证
      */

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);
      /*
      store 方法接受一个 Illuminate\Http\Request 实例参数，我们可以使用该参数来获得用户的所有输入数据。如果表单中包含一个 name 字段，则可以借助 Request 使用下面的这种方式来获取 name 的值：$name = $request->name;如果需要获取用户输入的所有数据，可使用：$data = $request->all();
      */


      /*
      session()->flash('success', '欢迎，您已注册成功！');
      由于 HTTP 协议是无状态的，所以 Laravel 提供了一种用于临时保存用户数据的方法 - 会话（Session），并附带支持多种会话后端驱动，可通过统一的 API 进行使用。
      我们可以使用 session() 方法来访问会话实例。而当我们想存入一条缓存的数据，让它只在下一次的请求内有效时，则可以使用 flash 方法。flash 方法接收两个参数，第一个为会话的键，第二个为会话的值.之后我们可以使用 session()->get('success') 通过键名来取出对应会话中的数据.
      */


      /*
      return redirect()->route('users.show', [$user]);
      用户模型 User::create() 创建成功后会返回一个用户对象，并包含新注册用户的所有信息。我们将新注册用户的所有信息赋值给变量 $user，并通过路由跳转来进行数据绑定。
      注意这里是一个『约定优于配置』的体现，此时 $user 是 User 模型对象的实例。route() 方法会自动获取 Model 的主键，也就是数据表 users 的主键 id，以上代码等同于：redirect()->route('users.show', [$user->id]);
      其中users.show路由名称来源于Route::resource('users', 'UsersController');
      */

      Auth::login($user);//用户注册成功后自动登录
      session()->flash('success','欢迎，新的开始！');
      return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user)
    {
      $this->authorize('update', $user);
      /*
      这里 update 是指授权类里(app/Policies/UserPolicy.php)的 update 授权方法，$user 对应传参 update 授权方法的第二个参数。正如定义 update 授权方法时候提起的，调用时，默认情况下，不需要 传递第一个参数，也就是当前登录用户至该方法内，因为框架会自动加载当前登录用户。
      */
      return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
      $this->validate($request, [
        'name' => 'required|max:50',
        'password' => 'nullable|confirmed|min:6'
      ]);

      $this->authorize('update', $user);

      $data = [];
      $data['name'] = $request->name;
      if ($request->password) {
        $data['password'] = bcrypt($request->password);
      }
      /*对传入的 password 进行判断，当其值不为空时才将其赋值给 data，避免将空白密码保存到数据库中*/

      $user->update($data);

      session()->flash('success', '个人资料跟新成功！');

      return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
      $this->authorize('destroy', $user); //使用 authorize 方法来对删除操作进行授权验证即可,参见用户策略app/Policies/UserPolicy.php的destroy方法
      $user->delete();
      session()->flash('success', '成功删除用户！');
      return back();

      /*
      在 destroy 动作中，我们首先会根据路由发送过来的用户 id 进行数据查找，查找到指定用户之后再调用 Eloquent 模型提供的 delete 方法对用户资源进行删除，成功删除后在页面顶部进行消息提示。最后将用户重定向到上一次进行删除操作的页面，即用户列表页。
      */
    }
}
