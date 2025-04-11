@extends('layouts.app')

@section('title', 'ダウンロード')

@section('content')
    <div class="container">
        <form method="GET" action="{{ route('csv.index') }}" class="search-form">
            @csrf
            <input type="date" name="date" class="form-control" id="date" value="{{ old('date') }}">
            <button type="submit" class="btn btn-primary">検索/解除</button>
        </form>

        <table>
            <tr>
                <th>日付</th>
                <th>客先名<br>現場名</th>
                <th>所属会社<br>作業者名</th>
                <th>種別 / 時間</th>
                <th>作業時間</th>
                <th>残業時間</th>
                <th>人役</th>
                <th>作業内容</th>
            </tr>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->work->cliant->cliant_name }}<br>{{ $attendance->site }}</td>
                    <td>
                        {{$attendance->craft->company->name}}<br>{{ $attendance->name }}</td>
                    <td>
                        {{ $attendance->work_type }} / {{ $attendance->time_type }}<br>
                        {{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}
                        ~
                        {{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}
                    </td>
                    <td>{{ $attendance->work_time}}</td>
                    <td>{{ $attendance->overtime }}</td>


                    <td>{{ $attendance->human_role }}</td>
                    <td>{{ $attendance->work_content }}</td>
                </tr>
            @endforeach
        </table>

        <form method="GET" action="{{ route('csv.download') }}" class="download-form">
            <input type="hidden" name="date" value="{{ request('date', now()->toDateString()) }}">
            <button type="submit" class="btn btn-success">CSVダウンロード</button>
        </form>
    </div>
@endsection

