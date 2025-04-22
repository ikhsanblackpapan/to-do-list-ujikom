<?php
namespace App\Http\Controllers;
use App\Models\Task;



use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('task.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
           
        ]);

        Task::create([
            'title' => $request->input('title'),
            
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function update(Request $request, Task $task)
{
    $request->validate([
        'title' => 'nullable|string|max:255',
        'completed' => 'nullable|boolean',
    ]);

    $task->update([
        'title' => $request->input('title', $task->title),
        'completed' => $request->input('completed', $task->completed),
        'priority' => $request->input('priority', $task->priority),
    ]);

    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Task updated successfully!']);
    }

    // Jika bukan AJAX, redirect kembali ke halaman index
    return redirect()->route('tasks.index')->with('success', 'Task status updated!');
}

    public function editTask(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->update([
            'title' => $request->input('title'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
{
    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
}

public function piority(){
    return view('task.piority');
}

public function jadwal(){
    return view('task.jadwal');
}
}
