<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App') - Laravel开发</title>
  </head>
  <body>
    @yield('content')
    {{--表示该占位区域将用于显示 content 区块的内容，而 content 区块的内容将由继承自 default 视图的子视图定义。--}}
  </body>
</html>
