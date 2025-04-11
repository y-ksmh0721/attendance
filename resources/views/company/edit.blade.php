@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2>所属会社名編集</h2>

    <div class="short-table">
        {{$company['name']}}<br>↓<br>
        <form action="{{route('company.update',['id'=>$company['id']])}}" method="POST">
            @csrf
            <input type="text" value="" name="company_name" required><br>
            <button type="submit" class="btn btn-success">{{ __('更新') }}</button>
        </form>
    </div>


    {{-- バリデーションエラー文 --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection


