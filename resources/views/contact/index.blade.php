@extends('layouts.app')

@section('contact', '共有事項')

@section('content')
    <div class="container">
        <div class="contact_area">
            <form action="{{ route('contact.confirm') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $user->name }}" name="name">
                <div class="form-group">
                    <span>名前：{{ $user->name }}</span>
                </div>
                <div class="form-group">
                    <select class="form-control site-select" name="site" required onchange="updateAvailableSites()">
                        <option value="" disabled selected>現場名を選択</option>
                        <option value="その他" >その他</option>
                        @foreach ($works as $work)
                            @if ($work->status === 'active')
                                <option value="{{ $work->name }}">{{ $work->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="title" placeholder="タイトル" value="{{old('title')}}">
                </div>
                <div class="form-group">
                    <textarea class="info-control" name="content" id="contact_content" cols="30" rows="10" placeholder="本文を入力してください">{{old('content')}}</textarea>
                </div>
                <button type="submit" class="btn">確認</button>
            </form>
        </div>
    </div>
@endsection
