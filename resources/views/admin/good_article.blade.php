@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
    </div>
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th width="13%">推荐用户</th>
                <th width="70%">推荐链接</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($lists as $list)
                <tr>
                    <td>{{ $list->id }}</td>
                    <td>{{ $list->user->wc_nickname }}</td>
                    <td>{{ $list->url }}</td>
                    <td>
                        <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                            @if(!$list->state)
                                <button class="btn btn-xs btn-info" onclick="examine(this);" data-url="{{ route('examine_article', $list->id) }}"style="width:40px;height: 26px;">审核</button>
                            @endif
                            <form action="{{ route('admin.delete_good_article', $list->id) }}" method="post">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-xs btn-danger">删除</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center">
            {{ $lists->links() }}
        </div>
    </div><!-- /.table-responsive -->
</div><!-- /span -->

<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="/admin/layer/layer.js"></script>
<script>
    function examine(th) {
        var content = '<form class="form-horizontal" style="margin-top: 20px">' +
            '<div class="form-group">' +
            '<label class="col-sm-3 control-label no-padding-right" style="margin: 4px 10px 0 0"> 文章链接：</label>' +
            '<input type="text" value="" class="col-sm-8 examine-article" >' +
            '</div>' +
            '</form>';
        layer.confirm(content, {
            btn: ['确定','取消'],
            skin: 'layui-layer-rim',
            area: ['500px', '220px']
        }, function(){
            var article_url = $('.examine-article').val(),
                url = $(th).attr('data-url');
            $.post(url, {url:article_url, _token:"{{ csrf_token() }}"}, function(ret){
                console.log(ret);
                if(ret.state == 0) {
                    layer.msg(ret.error, {icon: 1});
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000)
                }
            });
        });
    }
</script>
@endsection