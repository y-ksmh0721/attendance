@extends('layouts.app')

@section('dashboard', '出勤フォーム')

@section('content')
<div class="container">
    <h2 class="mb-4">職人登録画面</h2>

    <!-- 職人登録フォーム -->
    @if(in_array($user['id'], [1, 2,]))
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
                            <select class="form-control" id="company_info" name="company_info" required>
                                <option value="" disabled {{ old('company_info') == '' ? 'selected' : '' }}>選択してください</option>
                                @foreach ($companys as $company)
                                    <option value="{{ $company }}"
                                        {{ old('company_info') == $company['name'] ? 'selected' : '' }}>
                                        {{ $company['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="craft_name" name="craft_name" value="{{ old('craft_name') }}" required>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary big-btn">確認</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <br>
    @endif
    <form method="GET" action="{{route('craft.index')}}">
        {{-- <label for="keyword" class="form-label">キーワード検索</label> --}}
        <input type="text" name="keyword" class="form-control" id="keyword" value="{{ old('keyword', request()->get('keyword')) }}" placeholder="名前や会社名を入力">
        <button type="submit" class="btn btn-primary">検索</button>
        <button><a href="{{route('craft.index')}}">解除</a></button>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>所属</th>
                <th>職人名</th>
                <th>アクティブ</th>
                @if(in_array($user['id'], [1, 2,]))
                <th>削除</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($craft as $record)
                    <tr style="{{ $record->status === 'inactive' ? 'background-color: #919090;' : '' }}">
                        <td>
                            @if($record->company)
                                {{ $record->company->name }}
                            @else
                                所属情報なし
                            @endif
                        </td>
                        <td>{{ $record->name }}</td>
                        <td>
                            <form action="{{ route('craft.toggleStatus', $record->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $record->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $record->status === 'active' ? '表示' : '非表示' }}
                                </button>
                            </form>
                        </td>
                        @if(in_array($user['id'], [1, 2]))
                            <td>
                                <form action="{{ route('craft.destroy', ['id' => $record->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="btn btn-danger delete-btn" data-id="{{ $record->id }}">削除</button>
                                </form>
                            </td>
                        @endif
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
