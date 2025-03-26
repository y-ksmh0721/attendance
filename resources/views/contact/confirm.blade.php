@extends('layouts.app')

@section('contact', '共有事項')

@section('content')
    <div class="container">
        <form action="{{ route('contact.complete') }}" method="POST">
            @csrf
            <div class="info-flex">
                <h3>[  {{$request->title}}  ]</h3>
                [本文]
                <p>{!! nl2br(htmlspecialchars($request->content)) !!}</p>
                <div class="info-sub">
                    <p>{{$request->name}}</p>
                    <p>{{ \Carbon\Carbon::parse($request->created_at)->format('Y年m月d日') }}</p>
                </div>
            </div>
            <input type="hidden" value="{{ $request->name }}" name="name">
            <input type="hidden" value="{{ $request->site }}" name="site">
            <input type="hidden" value="{{ $request->title }}" name="title">
            <input type="hidden" value="{{ $request->content }}" name="content">
            <button type="submit" class="btn">確認画面へ</button>
        </form>
    </div>
@endsection
