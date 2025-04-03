@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2 class="mb-4">確認画面</h2>

    <!-- 出勤記録フォーム -->
    <form action="{{ route('attendance.complete') }}" method="post">
        @csrf
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <input type="hidden" name="work_type" value="{{ $attendance['work_type'] }}">
        <input type="hidden" name="date" value="{{ $attendance['date'] }}">
        <input type="hidden" name="name" value="{{ $attendance['name'] }}">
        @foreach($attendance['other_work_content'] as $otherWorkContent)
        <input type="hidden" name="other_work_content[]" value="{{ $otherWorkContent}}">
        @endforeach
        <table class="table table-bordered">
            <tr>
                <th>科目</th>
                <th>出勤日</th>
                <th>名前</th>
            </tr>
            <tr>
                <td>{{ $attendance['work_type']}}</td>
                <td>{{ $attendance['date'] }}</td>
                <td>{{ $attendance['name']}}</td>
            </tr>
            <tr>
                <th>今日の現場</th>
                <th>作業内容</th>
                <th>終了時間</th>
            </tr>
            @foreach ($attendance['site'] as $index => $site)
                <tr>
                    <td><!-- 今日の現場 -->
                        {{ $site }}
                        <input type="hidden" name="site[]" value="{{$site}}">
                    </td>
                    <td> <!-- 作業内容 -->
                        {{ $attendance['work_content'][$index] }}
                        <input type="hidden" name="work_content[]" value="{{$attendance['work_content'][$index]}}">
                    </td>
                    <td> <!-- 終了時間 -->
                        {{ $attendance['end_time'][$index]}}
                        <input type="hidden" name="end_time[]" value="{{$attendance['end_time'][$index]}}">
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="d-flex justify-content-start">
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正</a>
            <button type="submit" class="btn btn-primary">確定</button>
        </div>
    </form>
</div>
@endsection

