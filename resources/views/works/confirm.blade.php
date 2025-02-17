{{-- @extends('layouts.app') --}}

@section('content')
<div class="container">
    <h2 class="mb-4">現場名登録画面</h2>

    <!-- 確認フォーム -->
    <form action="{{route('works.complete')}}" method="post">
        @csrf
        {{-- {{ dd($cliant['cliant_info']['id']) }} --}}

        <div class="confirm_form">
            <div class="cliant_name">
                顧客名
            </div>
            <input type="hidden" name="cliant_id" value="{{ $cliant['cliant_info']['id'] }}">
            <input type="hidden" name="cliant_name" value="{{ $cliant['cliant_info']['cliant_name'] }}">
            <p>
                {{ $cliant['cliant_info']['cliant_name'] }}
            </p>
            <div class="site_title">
                現場名
            </div>
            <input type="hidden" name="site_name" value="{{ $work->site_name }}">
            <p>
                {{ $work->site_name }}
            </p>
        </div>
        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">登録する</button>
    </form>
</div>
{{-- @endsection --}}
