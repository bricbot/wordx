@extends('layouts.default')
@section('subtitle', '家长/助教检查')

@section('content')
<div class="row" style="margin-top:10px;margin-bottom:10px;">
        <div class="col col-md-4 col-md-offset-4 text-center">
            <h3>共需要上传 {{ $total_pages }} 张照片，已上传 {{ $uploaded_pages }} 张照片</h3>
            <small>UUID: {{ $paper_uuid }}</small>
            <br />
            <small>{{ $paper_name }} - {{ $student_name }}</small>
            <hr />
            @if ( $total_pages !== null && $uploaded_pages < $total_pages )
            <form method="POST" enctype="multipart/form-data" action="{{ route('papers.upload_and_permit', $paper_uuid) }}" >
                {{ csrf_field() }}
                <div>== 请拍照并上传完成的试卷 ==</div>
                <br />
                @for ( $i = 0; $i < ($total_pages - $uploaded_pages); $i++ )
                    第 {{ ($i+1) }} 页：<input type="file" name="pic_img_{{ ($i+1) }}" />
                    <br />
                @endfor
                <input type="hidden" name="total_img" value="{{ $total_pages }}"/>
                <div>
                    <input type="checkbox" name="completed" id="" /> 我已检查，试卷已经完成，并已拍照
                </div>
                <br />
                <button class="btn btn-success" type="submit" > 提交 </button>
            </form>
            @endif
        </div>
@stop