<header class="navbar navbar-fixed-top navbar-inverse">
  <div class="container">
    <div class="col-md-offset-1 col-md-10">
      <a href="/" id="logo">Sample App</a>
      <nav>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ route('help') }}">帮助</a></li>
          {{--
            {{　}} 是在 HTML 中内嵌 PHP 的 Blade 语法标识符，表示包含在该区块内的代码都将使用 PHP 来编译运行。route() 方法由 Laravel 提供，通过传递一个具体的路由名称来生成完整的 URL。
            --}}
          <li><a href="#">登录</a></li>
        </ul>
      </nav>
    </div>
  </div>
</header>
