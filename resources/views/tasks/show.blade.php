@extends('layouts.app')

@section('content')

    <h1>id = {{ $task->id }} のメッセージ詳細ページ</h1>

    <table class="table table-bordered">
        <tr>
            <th>id</th>
            <td>{{ $task->id }}</td>
        </tr>
        <tr>
            <th>タスク名</th>
            <td>{{ $task->content }}</td>
        </tr>
        <tr>
            <th>ステータス</th>
            <td>{{ $task->status }}</td>
        </tr>
    </table>

    @if (Auth::id() == $task->user_id)
    {{-- メッセージ編集ページへのリンク --}}
    {!! link_to_route('task.edit', 'このタスクを編集', ['task' => $task->id], ['class' => 'btn btn-light']) !!}
    {{-- メッセージ削除フォーム --}}
    {!! Form::model($task, ['route' => ['task.destroy', $task->id], 'method' => 'delete']) !!}
        {!! Form::submit('削除', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
    @endif
@endsection