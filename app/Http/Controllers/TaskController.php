<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $task = new Task;
        $task->user_id = Auth::user()->user_id;
        $task->title = $request->title;
        $task->description = $request->description;

        if($task->save()){
            return response()->json([ 'message' => "Data Successfully Added"]);
        }
    }

    public function index($check)
    {
        $user = Auth::user();
        $tasks = DB::table('tasks')
        ->select('*')
        ->where('user_id', Auth::user()->user_id)
        ->where('checked', $check)
        ->get();
        
        return response()->json(['tasks' => $tasks]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $task = Task::where('task_id', $id)->where('user_id', $user->user_id)->first();
        
        return response()->json(['task' => $task]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('task_id', $id)
        ->where('user_id', Auth::User()->user_id)
        ->first();

        $task->title = $request->title;
        $task->description = $request->description;

        if($task->save()){
            return response()->json([ 'message' => "Data Successfully Updated"]);
        }
    }

    public function check(Request $request, $id)
    {
        $task = Task::where('task_id', $id)
        ->where('user_id', Auth::User()->user_id)
        ->first();

        $task->checked = $request->checked;

        if($task->save()){
            return response()->json([ 'message' => "Data Successfully Updated"]);
        }
    }

    public function checkN(Request $request, $check)
    {
        $id = $request->json()->get("id");
        $i = 0;
        for($i = 0; $i < count($id); $i++){
            $task = Task::where('task_id', $id[$i])
            ->where('user_id', Auth::User()->user_id)
            ->first();
            $task->checked = $check;
            $task->save();
        }
        return response()->json([ 'message' => "Data Successfully Updated"]);
    }


    public function destroy($id)
    {
        $task = Task::where('task_id', $id)
        ->where('user_id', Auth::User()->user_id)
        ->first();
        
        if($task->delete()){
            return response()->json([ 'message' => "Data Successfully Deleted"]);
        }
    }
}
