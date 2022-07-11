<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
            return view('tasks.index');
        }
        $data = [];
        // 認証済みユーザを取得
        $user = \Auth::user();
        // ユーザの投稿の一覧を作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
        $data = [
            'user' => $user,
            'tasks' => $tasks,
        ];
        return view('tasks.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
            return redirect('/');
        }
        //
        $task = new Task;
        
        return view('tasks.create',['task'=>$task,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
           return redirect('/');
        }
        // バリデーション
        
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',   // 追加
        ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status, 
        ]);
        

        // トップページへリダイレクト
        return redirect('/');
        // return back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
           return redirect('/');
        }
        $task = Task::findOrFail($id);
        if(\Auth::id() !== $task->user_id){
        return redirect('/');
        }
        //idの値でメッセージを検索して取得
        
        // タスク詳細ビューでそれを表示
        return view('tasks.show', ['task' => $task,]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
            return redirect('/');
        }
        //idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        //　認証ユーザとタスクのユーザが同じかチェック
        if(\Auth::id() !== $task->user_id){
        return redirect('/');
        }
        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
            return redirect('/');
        }
        //idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
         //　認証ユーザとタスクのユーザが同じかチェック
        if(\Auth::id() !== $task->user_id){
        return redirect('/');
        }
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',   // 追加
        ]);
         // メッセージを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        // トップページへリダイレクト
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済みかチェック、falseでトップへ
        if(!\Auth::check()){
            return redirect('/');
        }
        //idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if(\Auth::id() !== $task->user_id){
        return redirect('/');
        }
        // メッセージを削除
            $task->delete();
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
