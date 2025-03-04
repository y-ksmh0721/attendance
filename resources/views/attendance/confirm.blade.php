@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2 class="mb-4">確認画面</h2>

    <!-- 出勤記録フォーム -->
    <form action="{{ route('attendance.complete') }}" method="post">
        @csrf
        <input type="hidden" name="work_type" value="{{ $attendance['work_type'] }}">
        <input type="hidden" name="date" value="{{ $attendance['date'] }}">
        <input type="hidden" name="name" value="{{ $attendance['name'] }}">
        @foreach($attendance['other_work_content'] as $otherWorkContent)
        <input type="hidden" name="other_work_content[]" value="{{ $otherWorkContent}}">
        @endforeach
        <table class="table table-bordered">
            <tr>
                <th>科目</th>
                <td>{{ $attendance['work_type']}}</td>
            </tr>
            <tr>
                <th>出勤日</th>
                <td>{{ $attendance['date'] }}</td>
            </tr>
            <tr>
                <th>名前</th>
                <td>{{ $attendance['name']}}</td>
            </tr>
            <tr>
                <th>終了時間</th>
                <td>{{ $attendance['end_time'] }}</td>
                <input type="hidden" name="end_time" value="{{ $attendance['end_time'] }}">
            </tr>
            <tr>
                <th>今日の現場</th>
                <th>作業内容</th>
            </tr>
            @foreach ($attendance['site'] as $index => $site)
                <tr>
                    <td>
                        {{ $site }}
                        <input type="hidden" name="site[]" value="{{$site}}">
                    </td> <!-- 今日の現場 -->
                    <td>
                        {{ $attendance['work_content'][$index] }}
                        <input type="hidden" name="work_content[]" value="{{$attendance['work_content'][$index]}}">
                    </td> <!-- 作業内容 -->
                </tr>
            @endforeach
        </table>

        <div class="d-flex justify-content-start">
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正する</a>
            <button type="submit" class="btn btn-primary">確定</button>
        </div>
    </form>
</div>
@endsection


{{-- <td>
                    @foreach($attendance['site'] as $site)
                     {{$site}}<br>
                     <input type="hidden" name="site[]" value="{{$site}}">
                    @endforeach
                </td> --}}


                                {{-- <td>
                    @foreach($attendance['work_content'] as $workContent)
                    {{$workContent}}<br>
                    <input type="hidden" name="work_content[]" value="{{$workContent}}">
                    @endforeach
                </td><input type="hidden" > --}}
