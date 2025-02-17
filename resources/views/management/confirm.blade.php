{{-- @extends('layouts.app') --}}

@section('content')
<div class="container">
    <h2 class="mb-4">現場名登録画面</h2>

    <!-- 確認フォーム -->
    <form action="{{route('management.complete')}}" method="post">
        @csrf
        <div class="confirm_form">
            <div class="cliant_title">
                客先名
            </div>
            <input type="hidden" name="cliant_name" value="{{ $cliant->cliant_name }}">
            <p>
                {{ $cliant->cliant_name }}
            </p>
        </div>
        <!-- 送信ボタン -->
        <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正する</a>
        <button type="submit" class="btn btn-primary">登録する</button>
    </form>
</div>
{{-- @endsection --}}
