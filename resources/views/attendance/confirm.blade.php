@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2 class="mb-4">確認画面</h2>

    <!-- 出勤記録フォーム -->
    <form action="{{ route('attendance.complete') }}" method="post">
        @csrf
        <table class="table table-bordered">
            <tr>
                <th>出勤日</th>
                <td>{{ $attendance['date'] }}</td>
                <input type="hidden" name="date" value="{{ $attendance['date'] }}">
            </tr>
            <tr>
                <th>午前の現場</th>
                <td>{{ $attendance['name']}}</td>
                <input type="hidden" name="name" value="{{ $attendance['name'] }}">
            </tr>

            <tr>
                <th>午前の現場</th>
                <td>{{ $attendance['morning_site'] }}</td>
                <input type="hidden" name="morning_site" value="{{ $attendance['morning_site'] }}">
            </tr>
            <tr>
                <th>午後の現場</th>
                <td>{{ $attendance['afternoon_site'] }}</td>
                <input type="hidden" name="afternoon_site" value="{{ $attendance['afternoon_site'] }}">
            </tr>
            <tr>
                <th>残業時間</th>
                <td>{{ $attendance['overtime'] }}</td>
                <input type="hidden" name="overtime" value="{{ $attendance['overtime'] }}">
            </tr>
        </table>

        <div class="d-flex justify-content-start">
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正する</a>
            <button type="submit" class="btn btn-primary">確定</button>
        </div>
    </form>
</div>
@endsection

