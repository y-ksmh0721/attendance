@extends('layouts.app')

@section('title', 'トップページ')

@section('content')
<div class="container">
    <a href="{{ route('login') }}"><button class="btn">ログイン</button></a>
    <br>
    <a href="{{ route('register') }}"><button class="btn">新規登録</button></a>
    <br>

</div>
@endsection

