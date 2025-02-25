@extends('layouts.app')

@section('complete', '完了')

@section('content')

<table class="full-width-table">
    <tr>
        <th>名前</th>
        <th>日付</th>
        <th>午前の現場</th>
        <th>午後の現場</th>
        <th>残業</th>
        <th>編集</th>
    </tr>
    @foreach ($attendance as $record)
    <tr>
        <td>{{$record->user->name}}</td>
        <td>{{$record->date}}</td>
        <td>{{$record->morning_site}}</td>
        <td>{{$record->afternoon_site}}</td>
        <td>{{$record->overtime}}</td>
        <td><a href="{{route('management.edit',['id'=>$record->id])}}">編集</a></td>
    </tr>
    @endforeach
</table>




@endsection

