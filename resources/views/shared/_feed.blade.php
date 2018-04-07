@if (count($feed_items))
<ol class="statuses">
  @foreach ($feed_items as $status)
    @include('statuses._status', ['user' => $status->user])
  @endforeach
  {!! $feed_items->render() !!}
</ol>
@endif
{{--<!--对数据进行了判断，当取出的数据不为空的时候才对视图进行渲染-->--}}
