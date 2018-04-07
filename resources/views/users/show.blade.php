@extends('layouts.default')
@section('title', $user->name)
@section('content')
<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="col-md-12">
      <div class="col-md-offset-2 col-md-8">
        <section class="user_info">
          @include('shared._user_info', ['user' => $user])
          {{--<!--通过给 @include 方法传参，将用户数据以关联数组的形式传送到 _user_info 局部视图上-->--}}
        </section>
      </div>
    </div>
    <div class="col-md-12">
      @if (count($statuses) > 0){{--<!--使用了 count($statuses) 方法来判断当前页面是否存在微博动态，如果不存在则不对微博的局部视图和分页链接进行渲染-->--}}
        <ol class="statuses">
          @foreach ($statuses as $status)
            @include('statuses._status')
          @endforeach
        </ol>
        {!! $statuses->render() !!}
      @endif
    </div>
  </div>
</div>
@stop
{{--
<!--UsersController控制器的show方法使用了 view('users.show', compact('user')) 将用户数据与视图进行绑定，因此在视图中可以直接使用 $user 来访问用户实例-->
  --}}
