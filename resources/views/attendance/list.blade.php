@extends('layouts.app')

@section('complete', '完了')

@section('content')
 <div class="container">

    <table class="table table-bordered full-width-table">
        <form method="GET" action="{{ route('attendance.list') }}">
            @csrf
            <tr>
                <td></td>
                <td></td>
                <td> <label for="">検索フォーム</label></td>
                <td>
                    <label for="start_date" class="form-label">開始日</label>
                    <input type="date" name="start_date" class="form-control" id="start_date" value="{{ old('start_date') }}">
                </td>
                <td>
                    <label for="end_date" class="form-label">終了日</label>
                    <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date') }}">
                </td>
                <td>                <label for="keyword" class="form-label">キーワード</label>
                    <input type="text" name="keyword" class="form-control" id="keyword" value="{{ old('keyword') }}" placeholder="名前や現場名を入力"></td>
                <td>
                    <button type="submit" class="btn btn-primary">検索/解除</button>
                </td>
            </tr>
        </form>
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


