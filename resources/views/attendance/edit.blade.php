@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2>出勤者情報</h2>
    <p>出勤者名：{{$attendance->name}}</p>
    <p>日付：{{$attendance->date}}/{{$attendance->day_of_week}}曜日</p>

    <div class="short-table">
        <form action="{{route('attendance.update',['id'=>$attendance->id])}}" method="POST">
            @csrf
            <input type="hidden" value="{{$attendance->name}}" name="user_id">
            <input type="hidden" value="{{$attendance->date}}" name="date">

            <table class="full-width-table">
                <tr>
                    <th>項目</th>
                    <th>変更前</th>
                    <th>変更後</th>
                </tr>
                <tr>
                    <td>日付</td>
                    <td>{{ $attendance->date}}</td>
                    <td>{{--　日付 --}}
                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    </td>
                </tr>
                <tr>
                    <td>現場名</td>
                    <td>{{$attendance->site}}</td>
                    <td>
                        <select class="form-control" id="morning_site" name="site" required>
                            <option value="" disabled {{ old('site') == '' ? 'selected' : '' }}>選択してください</option>
                            <option value="休み" {{ old('site') == '休み' ? 'selected' : '' }}>休み</option>
                            @foreach ($works as $work)
                                @if ($work->status === 'active')
                                    <option value="{{ $work->name }}" {{ old('site') == $work->name ? 'selected' : '' }}>
                                        {{ $work->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>時間</td>
                    <td>{{ $attendance->time_type}}</td>
                    <td>{{-- 時間 --}}
                        <select class="form-control" id="time_type" name="time_type" required>
                            <option value="" disabled {{ old('time_type') == '' ? 'selected' : '' }}>選択してください</option>
                            <option value="終日" {{ old('time_type') == '終日' ? 'selected' : '' }}>終日</option>
                            <option value="半日" {{ old('time_type') == '半日' ? 'selected' : '' }}>半日</option>
                            <option value="夜勤" {{ old('time_type') == '夜勤' ? 'selected' : '' }}>夜勤</option>
                            <option value="残業のみ" {{ old('time_type') == '残業のみ' ? 'selected' : '' }}>残業のみ</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>終了時間</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</td>
                    <td>
                        <input type="time" class="form-control end-time" name="end_time" value="17:00" required>
                    </td>
                </tr>
                <tr>
                    <td>種別</td>
                    <td>{{ $attendance->work_type}}</td>
                    <td>
                        <select class="form-control" id="work_type" name="work_type" required>
                            <option value="" disabled {{ old('time_type') == '' ? 'selected' : '' }}>選択してください</option>
                            <option value="請負" {{ old('work_type') == '請負' ? 'selected' : '' }}>請負</option>
                            <option value="外注" {{ old('work_type') == '外注' ? 'selected' : '' }}>外注</option>
                            <option value="常用" {{ old('work_type') == '常用' ? 'selected' : '' }}>常用</option>
                        </select>
                    </td>
                </tr>
            </table>
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


