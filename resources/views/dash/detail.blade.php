@extends('layouts.default')
@section('subtitle', '试卷详情')

@section('content')
<div class="row" style="margin-top:22px;">
    <div class="col-9">
        <h1> {{ $paper_alias }} - {{ $student }} </h1>
        <div><small style="font-size:18px;">
            @if ($status['corrected'] === 1) 
            （学生已订正）
            @elseif ($status['confirm'] === 1)
            （家长已检查）
            @else
            （等待学生完成）
            @endif
        </small></div>
    </div>
    <div class="col-3">{!! QrCode::size(100)->generate(route('papers.index', 'sadfasdf')); !!}</div>
</div>
<div class="row" style="margin-top:30px;">
    <div class="col">
        <span>
            操作： 
            <a>允许批改</a>
            | 
            <span>
                查看原卷（
                @foreach ($imgs as $img)
                <a href="/papers_img/{{ $paper_uuid }}/paper_page_{{ $img['order'] }}_{{ $img['upload_by'] }}.pdf" target="_blank">【{{ $img['order'] }}】</a>  
                @endforeach
                ）
            </span>
        </span>
    </div>
</div>
<div class="row" style="margin-top:30px;">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%;">题目</th>
                        <th style="border-right: 2px solid #000;width: 25%;">批改状态</th>
                        <th style="border-left: 2px solid #000;width: 25%;">题目</th>
                        <th style="width: 25%;">批改状态</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($quiz_ids); $i += 2)
                    <tr>
                        {{--  左边  --}}
                        <td>{{ $quizes[$i]['show'] }}</td>
                        <td style="border-right: 2px solid #000;">
                            <small>
                                @if ($self_corrections[$i]['result'] == '1')
                                <span style="color:green;">正确</span>
                                @elseif ($self_corrections[$i]['result'] == '-1')
                                <span style="color:red;">错误</span>
                                @endif
                                 | 
                                 @if ($self_corrections[$i]['comment'] == 'forget')
                                <span style="color:dodgerblue;">不认识</span>
                                @elseif ($self_corrections[$i]['comment'] == 'cannot_spell')
                                <span style="color:darkslateblue;">不会拼</span>
                                @else
                                <span style="color:darkslateblue;">——</span>
                                @endif
                                 | 
                                <span>
                                    <a href="#">添加批注</a>
                                </span>
                            </small>
                        </td>

                        {{--  右边  --}}
                        <td style="border-left: 2px solid #000;">{{ $quizes[($i+1)]['show'] }}</td>
                        <td>
                            <small>
                                @if ($self_corrections[($i+1)]['result'] == '1')
                                <span style="color:green;">正确</span>
                                @elseif ($self_corrections[($i+1)]['result'] == '-1')
                                <span style="color:red;">错误</span>
                                @endif
                                    | 
                                    @if ($self_corrections[($i+1)]['comment'] == 'forget')
                                <span style="color:dodgerblue;">不认识</span>
                                @elseif ($self_corrections[($i+1)]['comment'] == 'cannot_spell')
                                <span style="color:darkslateblue;">不会拼</span>
                                @else
                                <span style="color:darkslateblue;">——</span>
                                @endif
                                    | 
                                <span>
                                    <a href="#">添加批注</a>
                                </span>
                            </small>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row" style="margin-top:15px;">
    <div class="col">
        <fieldset>
            <legend><small>整体批改意见</small></legend><textarea></textarea>
            <div><button class="btn btn-primary btn-sm" type="button">提交</button></div>
        </fieldset>
    </div>
</div>
<div class="row" style="margin-top:15px;">
    <div class="col text-center"><span>测试学校 - 测试学科</span></div>
</div>
@stop