@extends('layouts.app')

@section('contact', '共有事項')

@section('content')
    <div class="container">
        <a href="{{ route('contact.index') }}">進捗を記録する</a>

        <div class="card-container">
        @foreach ($contacts as $contact)
        <div class="card">
            <a href="{{route('contact.info', $contact->id)}}" class="card_a">
                <h3>{{ $contact->title }}</h3>
                <p>現場名：{{ $contact->site }}</p>
                <p>更新日：{{ \Carbon\Carbon::parse($contact->created_at)->format('Y年m月d日') }}</p>
                <p>記録者：{{ $contact->name }}</p>
            </a>
        </div>
            @endforeach
        </div>
        {{ $contacts->appends(request()->query())->links() }}
    </div>
@endsection
