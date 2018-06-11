@extends('layouts.default')
@section('subtitle', '订正试卷')
@section('jsfile', 'papers/student.js')

@section('content')
    @if ($show_interface === 1)
    <div id="self_correct_interface">
        <div class="row">
            <div class="col-md-12">
                <h1>自主订正<small>试卷名</small></h1>
            </div>
        </div>
        <div class="row">
            @if ($correction_data['quizes'] !== null && $correction_data['quiz_ids'] !== null)
                @foreach ($correction_data['quizes'] as $key => $quiz)
                @if ($key === 0)
                <div class="key_paper first current" data-quiz-id="{{ $correction_data['quiz_ids'][$key] }}">
                @else
                    @if (($key+1) === count($correction_data['quizes']))
                    <div class="key_paper last hidden" data-quiz-id="{{ $correction_data['quiz_ids'][$key] }}">
                    @else
                    <div class="key_paper hidden" data-quiz-id="{{ $correction_data['quiz_ids'][$key] }}">
                    @endif
                @endif
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <div><strong>题面</strong></div>
                        <div><span>{{ ($key+1) }}、{{ $quiz['show'] }}</span></div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="border-left: 1px solid #000;">
                        <div><strong>答案</strong></div>
                        <div><span>{{ $quiz['correction']['meaning'] }}</span></div>
                        <div><i>{{ $quiz['optional']['omeaning'] }}</i></div>
                        <div role="group" class="btn-group" style="margin-top:15px;">
                            <button class="btn btn-success" type="button" data-quiz-id="{{ $correction_data['quiz_ids'][$key] }}">答对</button>
                            <button class="btn btn-danger" type="button" data-quiz-id="{{ $correction_data['quiz_ids'][$key] }}">答错</button>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div><span>为什么做错了</span></div>
                <div role="group" class="btn-group" style="margin-top:10px;"><button class="btn btn-primary" type="button">我不认识</button><button class="btn btn-info" type="button">我拼不出</button></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <a class="badge btn-prev" style="background-color:rgb(30,203,182);font-size:15px;">&lt;PREV</a>
                <a class="badge btn-next" style="background-color:rgb(30,203,182);font-size:15px;">NEXT&gt;</a>
            </div>
        </div>
        <div class="row">
                <div class="col-md-12">
                    <hr />
                </div>
            </div>
        <div id="submit_interface" class="hidden">
            <div class="col-md-12 text-center">
                <form method="POST" action="{{ route('papers.sumbit', $paper_uuid) }}">
                    {{ csrf_field() }}
                    @foreach ($correction_data['quiz_ids'] as $key => $id)
                    <input id="quiz_{{ $key }}" class="inputHidden" type="hidden" name="quiz_{{ $key }}" value="" data-quiz-id="{{ $id }}" />
                    @endforeach
                    <input type="hidden" name="student_id" value="{{ $student_id }}">
                    <div>
                        <button class="btn btn-success" type="submit">
                            完成批改
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" style="margin-top:15px;margin-bottom:10px;bottom: 0px;">
            <div class="col-md-12 text-center">
                <div><span>学科 - 学生名</span></div>
            </div>
        </div>
    </div>
    @endif
@stop