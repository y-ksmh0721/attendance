{{-- {{dd($attendance->user->name)}} --}}

出勤者名：{{$attendance->user->name}}
<br><br>
日付：{{$attendance->date}}
<br><br>

    <table>
        <tr>
            <th>午前の現場</th>
            <th>午後の現場</th>
            <th>残業</th>
        </tr>
        <form action="{{route('management.update',['id'=>$attendance->id])}}" method="POST">
            @csrf
            <input type="hidden" value="{{$attendance->date}}" name="date">
            <input type="hidden" value="{{$attendance->user_id}}" name="user_id">
            <tr>
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
                        <label for="overtime" class="form-label"></label>
                        <input type="time" class="form-control" id="overtime" name="overtime" value="{{ old('overtime') }}" required>
                    </div>
                </td>
                <td>
                    <button type="submit" class="btn btn-success">{{ __('更新') }}</button></td>
            </tr>
        </form>
    </table>
