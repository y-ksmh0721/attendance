@extends('layouts.app')

@section('complete', '完了')

@section('content')
    <div class="short-table">
        <table>
            <tr>
                <th>人事管理</th>
                <th>現場管理</th>
            </tr>
            <tr>
                <td><a href="{{route('company.list')}}">所属登録</a></td>
                <td><a href="{{route('cliant.list')}}">客先登録</a></td>
            </tr>
            <tr>
                <td><a href="{{route('craft.index')}}">作業員登録</a></td>
                <td><a href="{{route('works.index')}}">現場登録</a></td>
            </tr>
            <tr>
                <td><a href="{{route('user.list')}}">ログイン管理</a></td>
                <td></td>
            </tr>
        </table>
    </div>
@endsection

