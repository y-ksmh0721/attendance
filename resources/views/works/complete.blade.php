@extends('layouts.app')

@section('complete', '完了')

@section('content')

送信完了しました
<br>
<a href="{{ route('works.index') }}">現場管理画面へ</a>

@endsection
