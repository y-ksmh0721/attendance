@extends('layouts.app')

@section('complete', '完了')

@section('content')

<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>メール</th>
            <th>権限</th>
            @if (Auth::user()->permission === 0)
             <th>編集</th>
            @endif
            <th>ログイン可否</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr style="{{$user->is_allowed == 0 ? 'background: #919090;' : ''}}">
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
                 @if (Auth::user()->permission === 0)
                <td><a href="{{route('user.edit',['id' => $user['id']])}}">編集</a></td>
                @endif
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
<script>
document.addEventListener("DOMContentLoaded", function () {
        // ページ読み込み時にスクロール位置を復元
        let scrollY = sessionStorage.getItem("scrollPosition");
        if (scrollY) {
            window.scrollTo(0, scrollY);
            sessionStorage.removeItem("scrollPosition"); // 一度使ったら削除
        }

        // すべてのフォーム送信時にスクロール位置を保存
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function () {
                sessionStorage.setItem("scrollPosition", window.scrollY);
            });
        });

        // 削除ボタン専用処理（確認ダイアログ付き）
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                if (confirm("本当に削除しますか？")) {
                    sessionStorage.setItem("scrollPosition", window.scrollY);
                    this.closest("form").submit();
                }
            });
        });
    });
    </script>
@endsection
