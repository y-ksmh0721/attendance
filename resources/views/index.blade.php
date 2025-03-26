@extends('layouts.app')

@section('title', 'トップページ')

@section('content')
<div class="container">
    Y'sTec出勤管理システムです。<br>
    不正のないように提出お願いします。<br><br>
    外注の方は「外注」にチェックを入れて<br>
    提出してください。<br>

    <a href="{{ route('login') }}"><button class="login-btn">ログイン</button></a>
    <br>


</div>
@endsection

