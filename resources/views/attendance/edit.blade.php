@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2>出勤者情報</h2>
    <p>出勤者名：{{$attendance->name}}</p>
    <p>日付：{{$attendance->date}}</p>
    <p>{{$attendance->day_of_week}}曜日</p>

    <form action="{{route('attendance.update',['id'=>$attendance->id])}}" method="POST">
        @csrf
        <input type="hidden" value="{{$attendance->name}}" name="user_id">
        <input type="hidden" value="{{$attendance->date}}" name="date">

        <table class="full-width-table">
            <tr>
                <th></th>
                <th>午前の現場</th>
                <th>午後の現場</th>
                <th>残業</th>
            </tr>
            <tr>
                <td>変更前</td>
                <td>{{$attendance->morning_site}}</td>
                <td>{{$attendance->afternoon_site}}</td>
                <td>{{ \Carbon\Carbon::parse($attendance->overtime)->format('H:i') }}</td>
            </tr>
            <tr>
                <td>変更後</td>
                <td>
                    <select class="form-control" id="morning_site" name="morning_site" required>
                        <option value="">選択してください</option>
                        <option value="休み">休み</option>
                        @foreach ($works as $work)
                            @if ($work->status === 'active')
                                <option value="{{ $work->name }}">{{ $work->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control" id="afternoon_site" name="afternoon_site" required>
                        <option value="">選択してください</option>
                        <option value="休み">休み</option>
                        @foreach ($works as $work)
                            @if ($work->status === 'active')
                                <option value="{{ $work->name }}">{{ $work->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="mb-3">
                        <input type="time" class="form-control" id="overtime" name="overtime" value="{{ old('overtime') }}" required>
                    </div>
                </td>
            </tr>
        </table>

        <button type="submit" class="btn btn-success">{{ __('更新') }}</button>
    </form>
</div>
@endsection


