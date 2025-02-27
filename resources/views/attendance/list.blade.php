@extends('layouts.app')

@section('complete', '完了')

@section('content')

<div class="container">
    <h2 class="mb-4">出勤表</h2>
    <table class="table table-bordered full-width-table">
        <tr>
            <th>日付</th>
            <th>曜日</th>
            <th>名前</th>
            <th>所属</th>
            <th>午前の現場</th>
            <th>午後の現場</th>
            <th>残業</th>
            @if(in_array($user['id'], [1, 2]))
            <th>編集</th>
            @endif
        </tr>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->date }}</td>
            <td>{{ $attendance->day_of_week}}</td>
            <td>{{ $attendance->name }}</td>
            <td>
                @if($attendance->craft && $attendance->craft->company)
                    {{ $attendance->craft->company->name }}
                @else
                     所属情報なし
                @endif
            </td>
            <td>{{ $attendance->morning_site }}</td>
            <td>{{ $attendance->afternoon_site }}</td>
            <td>{{ substr($attendance->overtime, 0, 5) }}</td>
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

