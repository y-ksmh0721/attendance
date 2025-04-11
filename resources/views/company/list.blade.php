@extends('layouts.app')

@section('complete', '完了')

@section('content')
<div class="container">
    <h2 class="mb-4">所属会社登録フォーム</h2>
    <form action="{{route('company.confirm')}}" method="post">
        @csrf

        <!-- 客先記入欄 -->
        <div class="mb-3">
            <label for="company_name" class="form-label">会社名を記入してください</label>
            <br>
            <input type="text" name='company_name' value="" required>
        </div>
        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">記録する</button>
    </form>

    <h2 class="mb-4">所属会社リスト</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>会社名</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companys as $company)
            <tr>
                <td>{{ $company['name'] }}</td>
                <td><a href="{{route('company.edit',['id' => $company['id']])}}">編集</a></td>
                <td>
                    <form action="{{ route('company.destroy', ['id' => $company['id']]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $company['id'] }}">
                            削除する
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                // 削除前にスクロール位置を保存
                const scrollPosition = window.scrollY;

                if (confirm("本当に削除しますか？")) {
                    // 削除フォームを送信
                    this.closest("form").submit();

                    // 削除後に元のスクロール位置に戻る
                    window.scrollTo(0, scrollPosition);
                }
            });
        });
    });
</script>
@endsection

