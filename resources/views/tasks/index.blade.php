@extends('layouts.app')

@section('content')

    <div class="prose ml-4">
        <h2>タスク 一覧</h2>
    </div>

    @if (isset($tasks))
        <table class="table table-zebra w-full my-4">
            <thead>
                <tr>
                    <th></th>
                    <th>ステータス</th>
                    <th>タスク名</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    <td><a class="link link-hover text-info" href="{{ route('tasks.show', $task->id) }}">詳細</a></td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->content }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- ページネーションのリンク --}}
        {{ $tasks->links() }}
    @endif
    
    
    {{-- タスク作成ページへのリンク --}}
    <a class="btn btn-primary" href="{{ route('tasks.create') }}">新規タスクの追加</a>

@endsection