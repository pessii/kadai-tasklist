<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class UsersController extends Controller
{
    public function index()     
    {
        // タスク一覧を取得
        $tasks = Task::orderBy('id', 'asc')->paginate(10);

        //　タスク一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);  
    }
    
    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);

        // タスク詳細ビューでそれを表示
        return view('users.show', [
            'task' => $task,
        ]);
    }
}