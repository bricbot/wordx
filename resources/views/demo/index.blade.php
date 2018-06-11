@extends('layouts.default')
@section('subtitle', 'Demo Manager')

@section('content')
<div class="row" style="margin-top:10px;margin-bottom:10px;">
        <div class="col col-md-4 text-center">
            <div>Questions：</div>
            <a class="btn btn-success" href="{{ route('demo.check_word_dt') }}" target="_blank">check_word_dt</a>
            <a class="btn btn-primary" href="{{ route('demo.refill_words') }}" target="_blank">refill_words</a>
        </div>
        <div class="col col-md-8"></div>
    </div>
<div class="row" style="margin-top:10px;margin-bottom:10px;">
    <div class="col col-md-4 text-center">
        <div>Accounts：</div>
        <a class="btn btn-success" href="#" target="_blank">list_acc</a>
        <a class="btn btn-primary" href="{{ route('demo.gen_character') }}" target="_blank">init_acc</a>
    </div>
    <div class="col col-md-8">
        <div>have {{ $user_data['count'] }} users:</div>
        @if ($user_data['count'] > 0)
        <div>
            <ul>
                @foreach ($user_data['users'] as $user)
                    <li>{{ $user['id'] }} - {{ $user['account'] }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
<div class="row" style="margin-top:10px;margin-bottom:10px;">
    <div class="col col-md-4 text-center">
        <div>Papers:</div>
        <form method="GET" action="{{ route('demo.new_paper') }}" target="_blank" >
            DATE: <input type="input" name="pratice_time" />
            {{--  <a class="btn btn-success" href="#" target="_blank">list_papers</a>  --}}
            <button type="submit" class="btn btn-primary" target="_blank">gen_paper</button>
        </form>
    </div>
    <div class="col col-md-8">
        <div>have {{ $paper_data['count'] }} papers:</div>
        @if ($paper_data['count'] > 0)
        <div>
            <ul>
                @foreach ($paper_data['papers'] as $paper)
                    <li>
                        {{ $paper['id'] }} - {{ $paper['alias'] }} ( 0 | 0 | 0 )
                        <a href="{{ route('papers.index', ['paper_uuid' => $paper['uuid']]) }}" target="_blank">Paper_Link</a> | 
                        <a href="#" target="_blank">QR</a>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
<div class="row" style="margin-top:10px;margin-bottom:10px;">
    <div class="col col-md-4 text-center">
        <div>Reset:</div>
        <a class="btn btn-primary" href="{{ route('demo.reset_all') }}" target="_blank">reset_all</a>
    </div>
    <div class="col col-md-8"></div>
</div>
@stop