@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2 class="mb-4">確認画面</h2>

    <!-- 現場名登録確認フォーム -->
    <form action="{{ route('company.complete') }}" method="post">
        @csrf
        <table class="table table-bordered">
            <tr>
                <th>所属名</th>
                <td>{{ $company }}</td>
                <input type="hidden" name="company_name" value="{{ $company }}">
            </tr>
        </table>

        <div class="d-flex justify-content-start">
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正する</a>
            <button type="submit" class="btn btn-primary">登録する</button>
        </div>
    </form>
</div>
@endsection

