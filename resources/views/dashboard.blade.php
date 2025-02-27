@extends('layouts.app')

@section('dashboard', 'ダッシュボード')

@section('content')
    {{-- ここからbody --}}
    <div class="container">
        <h2 class="mb-4">出勤管理</h2>
        <!-- 出勤記録フォーム -->
        <form action="{{route('attendance.confirm')}}" method="post">
            @csrf
            <!-- 日付選択 -->
            <div class="mb-3">
                <label for="date" class="form-label">日付</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <!-- 午前の現場 -->
            <div class="mb-3">
                <label for="name" class="form-label">職人名</label>
                <select class="form-control" id="name" name="name" required>
                    <option value="">選択してください</option>
                    @foreach ($crafts as $craft)
                    @if ($craft->status === 'active')
                        <option value="{{ $craft->name }}">{{ $craft->name }}</option>
                    @endif
               @endforeach
                </select>
            </div>
            <!-- 午前の現場 -->
            <div class="mb-3">
                <label for="morning_site" class="form-label">午前の現場</label>
                <select class="form-control" id="morning_site" name="morning_site" required>
                    <option value="">選択してください</option>
                    <option value="休み">休み</option>
                    @foreach ($works as $work)
                    @if ($work->status === 'active')
                        <option value="{{ $work->name }}">{{ $work->name }}</option>
                    @endif
               @endforeach
                </select>
            </div>
            <!-- 午後の現場 -->
            <div class="mb-3">
                <label for="afternoon_site" class="form-label">午後の現場</label>
                <select class="form-control" id="afternoon_site" name="afternoon_site" required>
                    <option value="">選択してください</option>
                    <option value="休み">休み</option>
                    @foreach ($works as $work)
                         @if ($work->status === 'active')
                             <option value="{{ $work->name }}">{{ $work->name }}</option>
                         @endif
                    @endforeach
                </select>
            </div>
            <!-- 残業時間（任意） -->
            <div class="mb-3">
                <label for="overtime" class="form-label">残業時間（任意）</label>
                <input type="time" class="form-control" id="overtime" name="overtime" value="{{ old('overtime') }}" required>
            </div>
            <!-- 送信ボタン -->
            <button type="submit" class="btn btn-primary">記録する</button>
        </form>
    </div>
    <a href="#" id="logout-link">ログアウト</a>

<script>
document.getElementById("logout-link").addEventListener("click", function (e) {
    e.preventDefault();

    let form = document.createElement("form");
    form.method = "POST";
    form.action = "{{ route('logout') }}";

    let csrf = document.createElement("input");
    csrf.type = "hidden";
    csrf.name = "_token";
    csrf.value = "{{ csrf_token() }}";

    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
});
</script>
    @endsection

