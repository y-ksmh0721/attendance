@extends('layouts.app')

@section('complete', '完了')

@section('content')

<div class="container">
    <h2 class="mb-4">出勤表</h2>
    <label for="">検索フォーム</label>
<form method="GET" action="{{ route('attendance.list') }}">
    {{-- 開始日 --}}
    <div class="mb-3 d-flex-form">
        <div class="me-3">
            <label for="start_date" class="form-label">開始日</label>
            <input type="date" name="start_date" class="form-control" id="start_date" value="{{ old('start_date') }}">
        </div>

        {{-- 終了日 --}}
        <div>
            <label for="end_date" class="form-label">終了日</label>
            <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date') }}">
        </div>
    </div>

    {{-- キーワード検索 --}}
    <div class="mb-3">
        <label for="keyword" class="form-label">キーワード</label>
        <input type="text" name="keyword" class="form-control" id="keyword" value="{{ old('keyword') }}" placeholder="名前や現場名を入力">
    </div>

    <button type="submit" class="btn btn-primary">検索</button>
</form>
    <table class="table table-bordered full-width-table">
        <tr>
            <th>日付</th>
            <th>客先名</th>
            <th>現場名</th>
            <th>作業者名</th>
            <th>時間</th>
            <th>終了時間</th>
            <th>残業</th>
            <th>種別</th>
            @if(in_array($user['id'], [1, 2]))
            <th>編集</th>
            @endif
        </tr>
        {{-- {{dd($attendances)}} --}}
        @foreach($attendances as $attendance)
        <tr>
            {{-- {{dd($attendance->work->cliant->cliant_name)}} --}}
            <td>{{ $attendance->date }}</td>{{-- 日付 --}}
            <td>{{ $attendance->work->cliant->cliant_name }}</td>{{-- 客先名 --}}
            <td>{{ $attendance->work->name }}</td>{{-- 現場名 --}}
            <td>{{ $attendance->name }}</td>{{-- 作業者名 --}}
            <td>{{ $attendance->time_type}}</td>
            <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</td>{{-- 終了時間 --}}
            <td>{{ substr($attendance->overtime, 0, 5) }}</td>
            <td> {{ $attendance->work_type}}</td>
            @if(in_array($user['id'], [1, 2]))
            <td><a href="{{route('attendance.edit',['id' => $attendance->id])}}">編集</a></td>
            @endif

        </tr>
        @endforeach
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                if (confirm("本当に削除しますか？")) {
                    this.closest("form").submit();
                }
            });
        });
    });
</script>

@endsection

{{-- @if($attendance->craft && $attendance->craft->company)
{{ $attendance->craft->company->name }}
@else
 所属情報なし
@endif --}}
