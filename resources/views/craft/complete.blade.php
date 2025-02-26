@extends('layouts.app')

@section('complete', '完了')

@section('content')

送信完了しました
<br>
<a href="{{ route('craft.index') }}">職人管理画面へ</a>

@endsection
