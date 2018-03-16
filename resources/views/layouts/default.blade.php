<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App') - Laravel 入门教程</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts._header')
    {{--<!--引用头部视图resources/views/layouts/_header.blade.php-->--}}
    <div class="container">
      <div class="col-md-offset-1 col-md-10">
        @include('shared._messages')
        @yield('content'){{--<!--定义content区块-->--}}
        @include('layouts._footer')
          {{--<!--引用尾部视图resources/views/layouts/_footer.blade.php-->--}}
      </div>
    </div>
  </body>
</html>
