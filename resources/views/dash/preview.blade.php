@extends('layouts.default')
@section('subtitle', '打印预览')

@section('content')
@include('shared._messages')
<div class="row" style="margin-top:22px;">
    <div class="col-6">
        <h1>XXX测试卷<small style="font-size:25px;">2018-06-09</small></h1>
    </div>
    <div class="col-3">{!! QrCode::size(100)->generate(route('papers.index', $preview_data['uuid'])); !!}</div>
</div>
<div class="row" style="margin-top:30px;">
    <div class="col">
        <h4>试卷预览：</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>题目</th>
                        <th style="border-right: 2px solid #000;">回答</th>
                        <th style="border-left: 2px solid #000;">题目</th>
                        <th>回答</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Text</td>
                        <td style="border-right: 2px solid #000;"></td>
                        <td style="border-left: 2px solid #000;">Text</td>
                        <td></td>
                    </tr>
                    @for ($i = 0; $i < $preview_data['count']; $i += 2)
                        {{--  左边  --}}
                    <tr>
                        <td>{{ $preview_data['quizes'][$i]['quiz'] }}</td>
                        <td style="border-right: 2px solid #000;"></td>

                        {{--  右边  --}}
                        <td style="border-left: 2px solid #000;">{{ $preview_data['quizes'][($i+1)]['quiz'] }}</td>
                        <td></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row" style="margin-top:30px;">
    <div class="col">
        <h4>答案预览：</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>题目</th>
                        <th style="border-right: 2px solid #000;">答案</th>
                        <th style="border-left: 2px solid #000;">题目</th>
                        <th>答案</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Text</td>
                        <td style="border-right: 2px solid #000;">Text</td>
                        <td style="border-left: 2px solid #000;">Text</td>
                        <td>Text</td>
                    </tr>
                    @for ($i = 0; $i < $preview_data['count']; $i += 2)
                        {{--  左边  --}}
                    <tr>
                        <td>{{ $preview_data['quizes'][$i]['quiz'] }}</td>
                        <td style="border-right: 2px solid #000;">{{ $preview_data['keys'][$i]['key'] }}（{{ $preview_data['keys'][$i]['exkey'] }}）</td>

                        {{--  右边  --}}
                        <td style="border-left: 2px solid #000;">{{ $preview_data['quizes'][($i+1)]['quiz'] }}</td>
                        <td>{{ $preview_data['keys'][($i+1)]['key'] }}（{{ $preview_data['keys'][($i+1)]['exkey'] }}）</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row" style="margin-top:15px;">
    <div class="col text-center"><span>测试学校 - 测试学科</span></div>
</div>
@stop