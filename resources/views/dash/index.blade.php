@extends('layouts.default')
@section('subtitle', '教师后台')

@section('content')
<div class="row" style="margin-bottom:26px;">
    <div class="col" style="margin-top:40px;">
        <h1>{{ $index_data['year'] }}
            <small>
                {{ $index_data['date'] }} 
                @if ($index_data['is_history'])
                (<a href="{{ route('dash.index') }}" target="_self">回今天</a>)
                @endif
            </small>
        </h1>
        <form method="GET" action="{{ route('dash.history') }}">
            <label>查看其他时间：</label>
            <input type="text" name="history_date" />
            <button class="btn btn-primary btn-sm" type="submit">确定</button>
        </form>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-2 offset-2"><span>完成率</span>
        <div class="text-center"><span style="font-size:52px;color:rgb(60,90,198);">{{ $index_data['rates']['complete_rate'] * 100 }}%</span></div>
    </div>
    <div class="col-2"><span>订正率</span>
        <div class="text-center"><span style="font-size:52px;color:rgb(85,173,16);">{{ $index_data['rates']['correction_rate'] * 100 }}%</span></div>
    </div>
    <div class="col-2"><span>错误率</span>
        <div class="text-center"><span style="font-size:52px;color:rgb(187,22,91);">{{ $index_data['rates']['error_rate'] * 100 }}%</span></div>
    </div>
</div>
<div class="row">
    @if (count($index_data['papers']) > 0)
        @foreach ($index_data['papers'] as $paper)
        <div class="col">
            <div>
                {{ $paper['order'] }}、{{ $paper['alias'] }}} - {{ $paper['student'] }}({{ $paper['student_id'] }}) | 
                <a href="{{ route('dash.detail', $paper['uuid']) }}" target="_blank">查看详情</a> | 
                <a href="{{ route('dash.preview', $paper['uuid']) }}" target="_blank">打印预览</a>
            </div>
            <div class="progress">
                <div class="progress-bar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{ $paper['progress'] * 100 }}%;">{{ $paper['progress'] * 100 }}%</div>
            </div>
        </div>
        @endforeach
    @else
    <div class="col text-center">
        <br />
        <b>今天没有安排试卷</b>
    </div>
    @endif
</div>
@stop