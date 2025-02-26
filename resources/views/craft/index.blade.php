@extends('layouts.app')

@section('dashboard', 'ダッシュボード')

@section('content')
<div class="container">
    <h2 class="mb-4">職人登録画面</h2>

    <!-- 現場登録フォーム -->
    <form action="{{route('craft.confirm')}}" method="post">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><label for="cliant_name" class="form-label">所属会社</label></th>
                    <th><label for="site_name" class="form-label">職人名</label></th>
                    <th>決定</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control" id="craft_name" name="company_info" required>
                            <option value="">選択してください</option>
                            @foreach ($companys as $company)
                                <option value="{{$company}}">{{ $company['name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="craft_name" name="craft_name" value="{{ old('craft_name') }}">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">確認画面へ</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <h2 class="mt-5">登録現場</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>職人名</th>
                <th>所属</th>
                <th>アクティブ</th>
                <th>削除</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($craft as $record)
            {{-- {{dd($craft-)}} --}}

            <tr>
                <td>{{ $record->name}}</td>
                <td>{{$record->company->name}}</td>
                <td>
                    <form action="{{ route('craft.toggleStatus', $record->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $record->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                            {{ $record->status === 'active' ? 'アクティブ' : '非アクティブ' }}
                        </button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('craft.destroy', ['id' => $record->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $record->id }}">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

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
