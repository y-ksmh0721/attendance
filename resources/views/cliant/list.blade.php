@extends('layouts.app')

@section('complete', '完了')

@section('content')
<div class="container">
    <h2 class="mb-4">客先登録フォーム</h2>
    <form action="{{route('cliant.confirm')}}" method="post">
        @csrf

        <!-- 客先記入欄 -->
        <div class="mb-3">
            <label for="cliant_name" class="form-label">客先名を記入してください</label>
            <br>
            <input type="text" name='cliant_name' value="" >
        </div>
        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">記録する</button>
    </form>

    <h2 class="mb-4">客先リスト</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>客先名</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cliants as $cliant)
            <tr>
                <td>{{ $cliant['cliant_name'] }}</td>
                <td>
                    <form action="{{ route('cliant.destroy', ['id' => $cliant['id']]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $cliant['id'] }}">
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
                if (confirm("本当に削除しますか？")) {
                    this.closest("form").submit();
                }
            });
        });
    });
</script>
@endsection
