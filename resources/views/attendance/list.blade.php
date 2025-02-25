@extends('layouts.app')

@section('complete', '完了')

@section('content')

<div class="container">
    <h2 class="mb-4">出勤表（{{$user->name}}）</h2>
    <table class="table table-bordered">
            <tr>
                <th>日付</th>
                <th>曜日</th>
                <th>午前の現場</th>
                <th>午後の現場</th>
                <th>残業</th>
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
                    <td>{{ $record->morning_site }}</td>
                    <td>{{ $record->afternoon_site }}</td>
                    <td>{{ substr($record->overtime, 0, 5)}}</td>
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
