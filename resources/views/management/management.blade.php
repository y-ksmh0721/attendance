@extends('layouts.app')

@section('complete', '完了')

@section('content')


<br>
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


@endsection

