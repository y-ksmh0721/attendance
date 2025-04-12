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
                <th>出勤日</th>
                <td>{{ $attendance['date'] }}</td>
            </tr>
            <tr>
                <th>名前</th>
                <td>{{ $attendance['name']}}</td>
            </tr>
            <tr>
                <th>科目</th>
                <td>{{ $attendance['work_type']}}</td>
            </tr>
            @foreach ($attendance['site'] as $index => $site)
            @php
                $siteObj = json_decode($site); // ここでJSON文字列をオブジェクトに変換
            @endphp

            <tr>
                <td>
                    作業時間
                </td>
                <td>
                    {{ $attendance['start_time'][$index] }}
                    <input type="hidden" name="start_time[]" value="{{ $attendance['start_time'][$index] }}">
                    〜
                    {{ $attendance['end_time'][$index] }}
                    <input type="hidden" name="end_time[]" value="{{ $attendance['end_time'][$index] }}">
                </td>
            </tr>
            <tr>
                <th>現場</th>
                <td>
                    {{ $siteObj->name ?? '不明な現場' }}
                    <input type="hidden" name="site[]" value="{{ $site }}">
                </td>
            </tr>
            <tr>
                <th>作業内容</th>
                <td>
                    {{ $attendance['work_content'][$index] }}
                    <input type="hidden" name="work_content[]" value="{{ $attendance['work_content'][$index] }}">
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

