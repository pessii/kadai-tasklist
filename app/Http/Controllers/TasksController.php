<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 
use App\Models\Task;

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('id', 'asc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            // tasks.indexビューでそれらを表示
            return view('tasks.index', $data);
        }else{
            return view('dashboard');
        }
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
         $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }
    
    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if($user->id == $task->user->id){
            // タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }else{
            return redirect('dashboard');
        }
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        $user = Auth::user();
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
            'user_id' => $user->id,
        ]);

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if($user->id == $task->user->id){
            // タスク編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }else{
            return redirect('dashboard');
        }
    }

    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        
            
            
        $user = Auth::user();
        $task = Task::findOrFail($id);

        if($user->id == $task->user->id){
            if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/dashboard');
            }
    
            // トップページへリダイレクトさせる
            return redirect('/dashboard')
                ->with('Delete Failed');
        }else{
            return redirect('dashboard');
        }
    }
}
