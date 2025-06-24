@extends('layouts.app')

@section('confirm', '確認')

@section('content')
<div class="container">
    <h2>出勤者情報</h2>
    <p>出勤者名：{{$attendance->name}}</p>
    <p>日付：{{$attendance->date}}/{{$attendance->day_of_week}}曜日</p>
    @if($attendance->user->name)
    <p>記録者：{{$attendance->user->name}}</p>
    @else
        情報がありません
    @endif
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
                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $attendance->date) }}" required>
                    </td>
                </tr>
                <tr>
                    <td>種別</td>
                    <td>{{ $attendance->work_type}}</td>
                    <td>
                        <select class="form-control" id="work_type" name="work_type" required>
                            <option value="" disabled {{ old('work_type') == '' ? 'selected' : '' }}>選択してください</option>
                            <option value="請負" {{ old('work_type', $attendance->work_type) == '請負' ? 'selected' : '' }}>請負</option>
                            <option value="外注" {{ old('work_type', $attendance->work_type) == '外注' ? 'selected' : '' }}>外注</option>
                            <option value="常用" {{ old('work_type', $attendance->work_type) == '常用' ? 'selected' : '' }}>常用</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>時間</td>
                    <td>{{ $attendance->time_type}}</td>
                    <td>{{-- 時間 --}}
                        <select class="form-control" id="time_type" name="time_type" required>
                            <option value="" disabled {{ old('time_type') == '' ? 'selected' : '' }}>選択してください</option>
                            <option value="日勤" {{ old('time_type', $attendance->time_type) == '半日' ? 'selected' : '' }}>日勤</option>
                            <option value="夜勤" {{ old('time_type', $attendance->time_type) == '夜勤' ? 'selected' : '' }}>夜勤</option>
                            <option value="残業のみ" {{ old('time_type', $attendance->time_type) == '残業のみ' ? 'selected' : '' }}>残業のみ</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>現場名</td>
                    <td>{{$attendance->site}}</td>
                    <td>
                        <select class="form-control" id="morning_site" name="site" required>
                            <option value="" disabled {{ old('site') == '' ? 'selected' : '' }}>選択してください</option>
                            @foreach ($works as $work)
                                @if ($work->status === 'active')
                                    <option value="{{ $work->name }}" {{ old('site', $attendance->site) == $work->name ? 'selected' : '' }}>
                                        {{ $work->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>開始時間</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}</td>
                    <td>
                        <input type="time" class="form-control start-time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($attendance->start_time)->format('H:i')) }}" required>

                    </td>
                </tr>
                <tr>
                    <td>終了時間</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</td>
                    <td>
                        <input type="time" class="form-control end-time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($attendance->end_time)->format('H:i')) }}" required>

                    </td>
                </tr>
                <tr>
                    <td>作業時間</td>
                    <td>{{ number_format($attendance->work_time, 1) }}</td>
                    <td>
                        <select class="form-control" id="work_time" name="work_time" required>
                            <option value="" disabled {{ old('work_time') == '' ? 'selected' : '' }}>選択してください</option>
                            @for ($i = 0; $i <= 16; $i++) <!-- 0から16までループして、0.5刻みで表示 -->
                                <option value="{{ number_format($i * 0.5, 1) }}" {{ old('work_time', $attendance->work_time) == number_format($i * 0.5, 1) ? 'selected' : '' }}>
                                    {{ number_format($i * 0.5, 1) }} <!-- 表示を0.0, 0.5, 1.0, 1.5, ...に -->
                                </option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>人役</td>
                    <td>{{$attendance->human_role}}</td>
                    <td>
                        <select class="form-control" id="human_role" name="human_role" required>
                            <option value="" disabled {{ old('human_role') == '' ? 'selected' : '' }}>選択してください</option>
                            @for ($i = 0; $i <= 10000; $i++) {{-- 0から0.1000までを0.0001刻み --}}
                            @php
                                $value = number_format($i * 0.0001, 4, '.', '');
                            @endphp
                            <option value="{{ $value }}" {{ old('human_role', $attendance->human_role) == $value ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endfor

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


