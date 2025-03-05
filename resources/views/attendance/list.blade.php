@extends('layouts.app')

@section('complete', '完了')

@section('content')

<div class="container">
    <h2 class="mb-4">出勤表</h2>
    <table class="table table-bordered full-width-table">
        <tr>
            <td></td>
            <td></td>
            <form method="GET" action="{{ route('attendance.list') }}">
                <td><h3>検索フォーム</h2></td>
            <td>
                <label for="start_date" class="form-label">開始日</label>
                <input type="date" name="start_date" class="form-control" id="start_date" value="{{ old('start_date') }}">
            </td>
            <td>
                <label for="end_date" class="form-label">終了日</label>
                <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date') }}">
            </td>
            <td>
                <input type="text" name="keyword" class="form-control" id="keyword" value="{{ old('keyword') }}" placeholder="名前や現場名を入力">
            </td>
            <td>
                <button type="submit" class="btn btn-primary">検索/解除</button>
            </td>
            </form>
        </tr>
        <tr>
            <th>日付</th>
            <th>客先名</th>
            <th>現場名</th>
            <th>作業者名</th>
            <th>時間</th>
            <th>終了時間</th>
            <th>残業</th>
            @if(in_array($user['id'], [1, 2]))
            <th>残業編集</th>
            <th>種別</th>
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
            @if(in_array($user['id'], [1, 2]))
            <td>
                <form action="{{ route('attendance.toggleOvertime', $attendance->id) }}" method="POST">
                @csrf
                <button type="submit" name="overtime_add">+</button>
                <button type="submit" name="overtime_remove">-</button>
                </form>
            </td>
            </form>
            <td>
                @if($attendance->work_type !== '外注')
                    <form action="{{ route('attendance.toggleStatus', $attendance->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $attendance->work_type === '請負' ? 'btn-success' : 'btn-secondary' }}">
                            {{ $attendance->work_type === '請負' ? '請負' : '常用' }}
                        </button>
                    </form>
                @else
                    <span class="badge bg-warning">外注</span>
                @endif
            </td>
            <td><a href="{{route('attendance.edit',['id' => $attendance->id])}}">編集</a></td>
            @endif

        </tr>
        @endforeach
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ページ読み込み時にスクロール位置を復元
        let scrollY = sessionStorage.getItem("scrollPosition");
        if (scrollY) {
            window.scrollTo(0, scrollY);
            sessionStorage.removeItem("scrollPosition"); // 一度使ったら削除
        }

        // すべてのフォーム送信時にスクロール位置を保存
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function () {
                sessionStorage.setItem("scrollPosition", window.scrollY);
            });
        });

        // 削除ボタン専用処理（確認ダイアログ付き）
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                if (confirm("本当に削除しますか？")) {
                    sessionStorage.setItem("scrollPosition", window.scrollY);
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
