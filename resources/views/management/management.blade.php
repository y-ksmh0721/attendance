@extends('layouts.app')

@section('complete', '完了')

@section('content')
    <div class="short-table">
        <table>
            <tr>
                <th>管理項目</th>
                <th>リンク</th>
            </tr>
            <tr>
                <td>客先名を登録</td>
                <td><a href="{{route('cliant.list')}}">客先登録</a></td>
            </tr>
            <tr>
                <td>現場名を登録</td>
                <td><a href="{{route('works.index')}}">現場登録</a></td>
            </tr>
            <tr>
                <td>ログインを認証
                </td>
                <td><a href="{{route('user.list')}}">ログイン管理</a></td>
            </tr>
        </table>
    </div>
@endsection

