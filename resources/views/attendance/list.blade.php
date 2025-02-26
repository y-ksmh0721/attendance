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
            <th>午前の現場</th>
            <th>午後の現場</th>
            <th>残業</th>
            @if(in_array($user['id'], [1, 2]))
            <th>編集</th>
            @endif
        </tr>
        @foreach($attendance as $record)
        <tr>
            <td>{{ $record->date }}</td>
            <td>
                @php
                    $daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
                    $dayOfWeek = $record->dow - 1;
                @endphp
                {{ $daysOfWeek[$dayOfWeek] }}
            </td>
            <td>{{ $record->name }}</td>
            <td>{{ $record->morning_site }}</td>
            <td>{{ $record->afternoon_site }}</td>
            <td>{{ substr($record->overtime, 0, 5) }}</td>
            @if(in_array($user['id'], [1, 2]))
            <td><a href="#">編集</a></td>
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

