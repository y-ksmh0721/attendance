@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2 class="mb-4">確認画面</h2>

    <!-- 職人登録確認フォーム -->
    <form action="{{ route('craft.complete') }}" method="post">
        @csrf
        <table class="table table-bordered">
            {{-- {{dd($company['company_info'])}} --}}
            <tr>
                <th>所属</th>
                <td>{{$company['company_info']['name']}}</td>
                <input type="hidden" name="company_id" value="{{$company['company_info']['id']}}">
                <input type="hidden" name="company_name" value="{{$company['company_info']['name']}}">
            </tr>
            <tr>
                <th>職人名</th>
                <td>{{ $company['craft_name'] }}</td>
                <input type="hidden" name="craft_name" value="{{ $company['craft_name'] }}">
            </tr>
        </table>

        <div class="d-flex justify-content-start">
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">修正する</a>
            <button type="submit" class="btn btn-primary">登録する</button>
        </div>
    </form>
</div>
@endsection

{{-- @endsection --}}
