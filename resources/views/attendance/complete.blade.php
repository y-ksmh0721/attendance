@extends('layouts.app')

@section('complete', '完了')

@section('content')

送信完了しました
<br>
<a href="{{route('dashboard')}}">出勤管理フォームへ</a>
@endsection
