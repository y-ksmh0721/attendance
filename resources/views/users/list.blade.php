@extends('layouts.app')

@section('complete', '完了')

@section('content')
<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>メール</th>
            <th>権限</th>
            <th>ログイン可否</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->permission === 0)
                        全権限
                    @elseif($user->permission === 1)
                        一部権限
                    @elseif($user->permission === 2)
                        権限なし
                    @elseif($user->permission === 3)
                        常用
                    @elseif($user->permission === 4)
                        外注
                    @endif
                </td>
                <td>{{ $user->is_allowed ? '許可' : '拒否' }}</td>
                <td>
                    {{-- <form action=""> --}}
                    <form method="POST" action="{{ route('user.toggle', $user) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">
                            {{ $user->is_allowed == '1' ? '拒否にする' : '許可にする' }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection