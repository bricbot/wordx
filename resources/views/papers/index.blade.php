@extends('layouts.default')
@section('subtitle', '浏览试卷')

@section('content')
<div class="row" style="margin-top:10px;margin-bottom:10px;">
    <div class="col col-md-4 text-center">
        <div>UUID：{{ $paper_uuid }}</div>
        <div>选择测试角色:</div>
        <a class="btn btn-success" href="{{ route('papers.student', ['paper' => $paper_uuid]) }}">学生</a>
        <a class="btn btn-primary" href="{{ route('papers.assistant', ['paper' => $paper_uuid]) }}">家长/助教</a>
        <a class="btn btn-info" href="{{ route('papers.teacher', ['paper' => $paper_uuid]) }}">老师</a>
    </div>
    <div class="col col-md-8"></div>
</div>
@stop