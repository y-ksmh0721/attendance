@extends('layouts.app')

@section('title', 'トップページ')

@section('content')
<div class="container">
    <a href="{{ route('login') }}"><button class="btn">ログイン</button></a>
    <br>
    <a href="{{ route('register') }}"><button class="btn">新規登録</button></a>
    <br>

    <a href="#" id="logout-link">ログアウト</a>

<script>
document.getElementById("logout-link").addEventListener("click", function (e) {
    e.preventDefault();

    let form = document.createElement("form");
    form.method = "POST";
    form.action = "{{ route('logout') }}";

    let csrf = document.createElement("input");
    csrf.type = "hidden";
    csrf.name = "_token";
    csrf.value = "{{ csrf_token() }}";

    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
});
</script>

</div>
@endsection

