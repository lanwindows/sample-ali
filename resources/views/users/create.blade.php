@extends('layouts.default')
@section('title', '注册')

@section('content')
<div class="col-md-offset-2 col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h5>注册</h5>
    </div>
    <div class="panel-body">
      @include('shared._errors')
      {{--<!--引用\shared\_errors.blade.php视图-->--}}
      <form method="POST" action="{{ route('users.store') }}">
        {{ csrf_field() }}
        {{--
          <!--使用 POST 方法提交表单时，Laravel 为了安全考虑，需提供一个 token（令牌）来防止应用受到 CSRF（跨站请求伪造）的攻击。修复该异常的方法很简单，只需要在表单元素中添加 Blade 模板提供的 csrf_field 方法即可。这段代码转换为 HTML 如下所示<input type="hidden" name="_token" value="fhcxqT67dNowMoWsAHGGPJOAWJn8x5R5ctSwZrAq">-->
        --}}
          <div class="form-group">
            <label for="name">名称：</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            {{--<!--Laravel 提供了全局辅助函数 old 来帮助在 Blade 模板中显示旧输入数据。这样当我们信息填写错误，页面进行重定向访问时，输入框将自动填写上最后一次输入过的数据。-->--}}
          </div>

          <div class="form-group">
            <label for="email">邮箱：</label>
            <input type="text" name="email" class="form-control" value="{{ old('email') }}">
          </div>

          <div class="form-group">
            <label for="password">密码：</label>
            <input type="password" name="password" class="form-control" value="{{ old('password') }}">
          </div>

          <div class="form-group">
            <label for="password_confirmation">确认密码：</label>
            <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
          </div>

          <button type="submit" class="btn btn-primary">注册</button>
      </form>
    </div>
  </div>
</div>
@stop
