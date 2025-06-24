@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2>ユーザー編集</h2>

    <div class="short-table">
        {{$user->name}}<br>↓<br>
        <form action="{{route('user.update',['id'=>$user['id']])}}" method="POST">
            @csrf
            <input type="text" value="{{ old('name', $user->name) }}" name="name" required><br>
                        <div class="mt-1">
                <label>
                    <input type="radio" name="role" value="0" {{ old('role') == '0' ? 'checked' : '' }}>
                    全権限あり
                </label><br>
                <label>
                    <input type="radio" name="role" value="1" {{ old('role') == '1' ? 'checked' : '' }}>
                    一部権限あり
                </label><br>
                <label>
                    <input type="radio" name="role" value="2" {{ old('role') == '2' ? 'checked' : '' }}>
                    権限なし
                </label><br>
                <label>
                    <input type="radio" name="role" value="3" {{ old('role') == '3' ? 'checked' : '' }}>
                    常用
                </label><br>
                <label>
                    <input type="radio" name="role" value="4" {{ old('role') == '4' ? 'checked' : '' }}>
                    外注
                </label>
            </div>
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
