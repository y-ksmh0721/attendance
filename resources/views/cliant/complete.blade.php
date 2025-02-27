@extends('layouts.app')

@section('complete', '完了')

@section('content')

送信完了しました
<br>
<a href="{{ route('cliant.list') }}">リストへ</a>

@endsection
