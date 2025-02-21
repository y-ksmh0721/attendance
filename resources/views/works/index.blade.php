@extends('layouts.app')

@section('dashboard', 'ダッシュボード')

@section('content')
<div class="container">
    <h2 class="mb-4">現場名登録画面</h2>

    <!-- 現場登録フォーム -->
    <form action="{{route('works.confirm')}}" method="post">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><label for="cliant_name" class="form-label">客先名</label></th>
                    <th><label for="site_name" class="form-label">現場名</label></th>
                    <th>決定</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control" id="cliant_name" name="cliant_info" required>
                            <option value="">選択してください</option>
                            @foreach ($cliants as $cliant)
                                <option value="{{$cliant}}">{{ $cliant['cliant_name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name') }}">
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
                <th>現場名</th>
                <th>アクティブ</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($works as $work)
            <tr>
                <td>{{ $work->name }}</td>
                <td>
                    <form action="{{ route('works.toggleStatus', $work->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $work->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                            {{ $work->status === 'active' ? 'アクティブ' : '非アクティブ' }}
                        </button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('works.destroy', ['id' => $work['id']]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $work['id'] }}">削除</button>
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
