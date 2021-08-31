<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskController extends Controller{

    public function index(){
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request){

        $name = $request->input('name');
        $completed = (boolean)$request->input('completed');

        $exists = DB::table('tasks')->where([
            'name'=> $name,
            'completed'=> $completed
        ])->first();

        if(empty($exists)):
            $task = new Task;
            $task->name = $name;
            $task->completed = $completed;
            $insert = $task->save();

            if($insert):
                $id = $insert->id();
            else:
                $id = 0;
            endif;
            return response()->json($id);
        endif;
        
        return response()->json(false);
    }

    public function show($id){
        $task = Task::where('id', $id)->first();
        return response()->json($task);
    }

    public function update(Request $request, $id){

        $task = Task::find($id);
        $task->name = $request->input('name');
        $task->completed = $request->input('completed');
        $update = $task->save();

        if($update):
            return response()->json($id);
        else:
            return response()->json(false);
        endif;
        
    }

    public function destroy($id){
        $delete = Task::where('id', $id)->delete();

        if($delete):
            return response()->json(true);
        endif;

        return response()->json(false);
    }
}
