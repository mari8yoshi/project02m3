<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Task;
use Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function __construct() 
{
    $this->middleware('auth'); 
}

    public function index()
    {
        //return view('tasks');
        //$tasks = Task::orderBy('deadline', 'asc')->get(); 
$tasks = Task::where('user_id',Auth::user()->id) 
->orderBy('deadline', 'asc')
->get();
 return view('tasks', [
            'tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'task' => 'required|max:255',
            'deadline' => 'required',
        ]);
        if ($validator->fails()) { 
            return redirect()
            ->route('tasks.index') 
            ->withInput() 
            ->withErrors($validator);
    }
    $task = new Task;
    $task->user_id = Auth::user()->id;
    $task->task = $request->task; 
    $task->deadline = $request->deadline; 
    $task->comment = $request->comment;;
    $task->save();
    return redirect()->route('tasks.index');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        return view('taskedit', ['task' => $task]);
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
        $validator = Validator::make($request->all(), [ 
            'task' =>'required|max:255',
            'deadline' => 'required',
            ]);
            if ($validator->fails()) { 
                return redirect()
                ->route('tasks.edit', $id) 
                ->withInput() 
                ->withErrors($validator);
            }$task = Task::find($id); 
            $task->task = $request->task;
            $task->deadline = $request->deadline;
            $task->comment = $request->comment; 
            $task->save();
            return redirect()->route('tasks.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
