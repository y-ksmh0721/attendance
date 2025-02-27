@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2 class="mb-4">確認画面</h2>

    <!-- 現場名登録確認フォーム -->
    <form action="{{ route('cliant.complete') }}" method="post">
        @csrf
        <table class="table table-bordered">
            <tr>
                <th>客先名</th>
                <td>{{ $cliant->cliant_name }}</td>
                <input type="hidden" name="cliant_name" value="{{ $cliant->cliant_name }}">
            </tr>
        </table>

        <div class="d-flex justify-content-start">
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正する</a>
            <button type="submit" class="btn btn-primary">登録する</button>
        </div>
    </form>
</div>
@endsection

