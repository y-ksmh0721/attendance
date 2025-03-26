@extends('layouts.app')

@section('contact', '共有事項')

@section('content')
    <div class="container">
        <a href="{{ route('contact.index') }}">進捗を記録する</a>

        <div class="info-flex">
            <h3>[  {{$contact->title}}  ]</h3>
            [本文]
            <p>{!! nl2br(htmlspecialchars($contact->content)) !!}</p>
            <div class="info-sub">
                <p>{{$contact->name}}</p>
                <p>{{ \Carbon\Carbon::parse($contact->created_at)->format('Y年m月d日') }}</p>
            </div>
        </div>
        <a href="{{route('contact.list')}}">戻る</a>
    </div>
@endsection
