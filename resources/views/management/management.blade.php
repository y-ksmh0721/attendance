@extends('layouts.app')

@section('complete', '完了')

@section('content')
<a href="{{route('works.index')}}">現場登録画面</a>
<br><br>
<form action="{{route('management.confirm')}}" method="post">
    @csrf

    <!-- 客先記入欄 -->
    <div class="mb-3">
        <label for="cliant_name" class="form-label">客先名を記入してください</label>
        <br>
        <input type="text" name='cliant_name' value="">
    </div>
    <!-- 送信ボタン -->
    <button type="submit" class="btn btn-primary">記録する</button>
</form>

<div class="mb-3">
    <label for="cliant_name" class="form-label">登録済みの客先</label>
    <select class="form-control" id="cliant_name" name="cliant_name" required>
        <option value="">選択してください</option>
        @foreach ($cliants as $cliant)
            <option value="{{ $cliant['cliant_name'] }}">{{ $cliant['cliant_name'] }}</option>
        @endforeach
    </select>
</div>
@endsection

