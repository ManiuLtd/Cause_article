@extends('admin.layout')
@section('content')
    <link rel="stylesheet" href="/admin/css/daterangepicker.css" />
    <div class="col-xs-12">
        <div class="page-header">
            <h1> {{v('headtitle')}} </h1>
        </div>
        <form action="{{ route('admin.extension_report') }}" class="form-inline" method="get" style="margin-bottom: 10px">
            <div class="row">
                <div class="col-xs-4 col-sm-3">
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon-calendar bigger-110"></i>
                    </span>
                        <input class="form-control" type="text" name="date_range_picker" id="id-date-range-picker-1" value="{{ request()->date_range_picker }}" style="margin: 0">
                    </div>
                </div>
                <button class="btn btn-sm btn-info" type="submit">搜索</button>
            </div>
        </form>
        <div class="table-responsive">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                @foreach($extension as $value)
                    @if($loop->first)
                        <tr>
                            <th>员工/时间</th>
                            @foreach($value->count as $k => $v)
                                <th>{{ $k }}</th>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
                </thead>

                <tbody>
                    @foreach($extension as $value)
                        <tr>
                            <td>{{ $value->account }}</td>
                            @foreach($value->count as $v)
                                @if($loop->last)
                                    <td style="color: red;font-weight: bold">{{ $v }}</td>
                                @else
                                    <td>{{ $v }}</td>
                                @endif
                            @endforeach
                        <tr>
                        @if($loop->last && !empty($value->tot_count))
                            <tr>
                                <td>总计</td>
                                @foreach($value->tot_count as $v)
                                    <td style="color: red;font-weight: bold">{{ $v }}</td>
                                @endforeach
                            <tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.table-responsive -->
    </div><!-- /span -->
    <script src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="/admin/js/bootstrap.min.js"></script>
    <script src="/admin/js/date-time/daterangepicker.min.js"></script>
    <script src="/admin/js/date-time/moment.min.js"></script>
    <script>
        //时间插件
        $('input[name=date_range_picker]').daterangepicker().prev().on(ace.click_event, function(){
            $(this).next().focus();
        });
    </script>
@endsection