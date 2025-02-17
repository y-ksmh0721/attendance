

@extends('layouts.app')

@section('title', 'トップページ')

@section('content')
<div class="container">
    <a href="{{ route('login') }}"><button class="btn">ログイン</button></a>
    <br>
    <a href="{{ route('register') }}"><button class="btn">新規登録</button></a>
    <br>
    <a href="{{ route('management') }}"><button class="btn">現場管理画面</button></a>
</div>
@endsection

