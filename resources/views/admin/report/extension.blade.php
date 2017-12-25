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
                    <th>员工/时间</th>
                    @foreach($head as $k => $v)
                        <th>{{ $k }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($extension as $value)
                    <tr>
                        <td>{{ $value->account }}</td>
                        @foreach($value->count as $v)
                            <td>{{ $v }}</td>
                        @endforeach
                    <tr>
                    @if($loop->last)
                        <tr>
                            <td>总计</td>
                            @foreach($value->tot_count as $v)
                                <td>{{ $v }}</td>
                            @endforeach
                        <tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection