@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    @if(has_menu($menu,'/admin/menu/create'))
    <button class="btn btn-success store" data-url="{{route('menu.create')}}" style="margin-bottom: 15px">新增栏目</button>
    @endif
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="center">
                    <label>
                        <input type="checkbox" class="ace"/>
                        <span class="lbl"></span>
                    </label>
                </th>
                <th>标题</th>
                <th>栏目等级</th>
                <th>图标</th>
                <th class="hidden-480">链接</th>
                <th class="hidden-480">排序</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
                <tr>
                    <td class="center">
                        <label>
                            <input type="checkbox" class="ace"/>
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td>{{$value['title']}}</td>
                    <td>顶级栏目</td>
                    <td class="hidden-480"><i class="{{$value['icon']}}"></i></td>
                    <td>{{$value['url']}}</td>
                    <td class="hidden-480">
                        {{$value['sort']}}
                    </td>
                    <td>@if($value['display'] == 0)隐藏@elseif($value['display'] == 1)显示@endif</td>
                    <td>
                        <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                            @if(has_menu($menu,'menu/edit'))
                            <button class="btn btn-xs btn-info store" data-url="{{route('menu.edit', ['id'=>$value['id']])}}" style="width:26px;height: 26px;">
                                <i class="icon-edit bigger-120"></i>
                            </button>
                            @endif
                            @if(has_menu($menu,'menu/delete'))
                            <form action="{{route('menu.destroy',['id'=>$value['id']])}}" method="post">
                                {{csrf_field()}}
                                {{ method_field('DELETE') }}
                                <a class="btn btn-xs btn-danger common" data-msg="此删除操作会把下级内容删除，请谨慎操作" style="width: 26px">
                                    <i class="icon-trash bigger-120"></i>
                                </a>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @if(!empty($value['children']))
                    @foreach($value['children'] as $child)
                        <tr>
                            <td class="center">
                                <label>
                                    <input type="checkbox" class="ace"/>
                                    <span class="lbl"></span>
                                </label>
                            </td>
                            <td>&nbsp;┗━{{$child['title']}}</td>
                            <td>二级栏目</td>
                            <td class="hidden-480"><i class="{{$child['icon']}}"></i></td>
                            <td>{{$child['url']}}</td>
                            <td class="hidden-480">{{$child['sort']}}</td>
                            <td>@if($child['display'] == 0)隐藏@elseif($child['display'] == 1)显示@endif</td>
                            <td>
                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                    @if(has_menu($menu,'menu/edit'))
                                    <button class="btn btn-xs btn-info store" data-url="{{route('menu.edit', ['id'=>$child['id']])}}" style="width:26px;height: 26px;">
                                        <i class="icon-edit bigger-120"></i>
                                    </button>
                                    @endif
                                    @if(has_menu($menu,'menu/delete'))
                                    <form action="{{route('menu.destroy',['id'=>$child['id']])}}" method="post">
                                        {{csrf_field()}}
                                        {{ method_field('DELETE') }}
                                        <a class="btn btn-xs btn-danger common" data-msg="此删除操作会把下级内容删除，请谨慎操作" style="width: 26px">
                                            <i class="icon-trash bigger-120"></i>
                                        </a>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if(!empty($child['children']))
                            @foreach($child['children'] as $c)
                                <tr>
                                    <td class="center">
                                        <label>
                                            <input type="checkbox" class="ace"/>
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━{{$c['title']}}</td>
                                    <td>三级栏目</td>
                                    <td class="hidden-480"><i class="{{$c['icon']}}"></i></td>
                                    <td>{{$c['url']}}</td>
                                    <td class="hidden-480">{{$c['sort']}}</td>
                                    <td>@if($c['display'] == 0)隐藏@elseif($c['display'] == 1)显示@endif</td>
                                    <td>
                                        <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                            @if(has_menu($menu,'menu/edit'))
                                            <button class="btn btn-xs btn-info store" data-url="{{route('menu.edit', ['id'=>$c['id']])}}" style="width:26px;height: 26px;">
                                                <i class="icon-edit bigger-120"></i>
                                            </button>
                                            @endif
                                            @if(has_menu($menu,'menu/delete'))
                                            <form action="{{route('menu.destroy',['id'=>$c['id']])}}" method="post">
                                                {{csrf_field()}}
                                                {{ method_field('DELETE') }}
                                                <a class="btn btn-xs btn-danger common" data-msg="将删除此栏目" style="width: 26px">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                @endif
                @endforeach
            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection