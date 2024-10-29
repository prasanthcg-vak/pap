<?php

namespace App\Http\Controllers;

use App\Models\Campaigns;
use App\Models\Categorys;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaigns::all(); // Get all campaigns for the dropdown
        $categories = Categorys::where('is_active', 1)->get();
        $tasks = Tasks::with(['campaign', 'status'])->where('is_active', 1)->get();
        return view('tasks.index', compact('tasks', 'campaigns','categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'name' => 'required|string|max:255',
            'date_required' => 'required|date',
            // 'task_urgent' => 'sometimes|boolean',
            'size_width' => 'required|integer',
            'size_height' => 'required|integer',
            'description' => 'required|string',
        ]);
        $date = $validatedData['date_required'];
        $formattedDate = \DateTime::createFromFormat('m/d/Y', $date)->format('Y-m-d');
        // dd($formattedDate);

        // Create a new task using the validated data
        Tasks::create([
            'campaign_id' => $validatedData['campaign_id'],
            'name' => $validatedData['name'],
            'date_required' => $formattedDate,
            'task_urgent' => $validatedData['task_urgent'] ?? 0, // Default to 0 if not checked
            'size_width' => $validatedData['size_width'],
            'size_height' => $validatedData['size_height'],
            'description' => $validatedData['description'],
            'status_id' => null, // Set this as needed
            'is_active' => 1
        ]);

        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tasks $task)
    {
        $campaigns = Campaigns::all(); // Get all campaigns for the dropdown
        $categories = Categorys::where('is_active', 1)->get();
        
        return view('tasks.edit', compact('task', 'campaigns','categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tasks $task)
    {
        // dd(auth()->check());
        $campaigns = Campaigns::all();
        $categories = Categorys::where('is_active', 1)->get();
        return view('tasks.show', compact('task', 'campaigns','categories'));
    }

    // Update the task
    public function update(Request $request, Tasks $task)
    {
        // dd($request->all());
        // Validate the incoming request data
        $validatedData = $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'name' => 'required|string|max:255',
            'date_required' => 'required|date',
            'task_urgent' => 'sometimes|boolean',
            'category_id' => 'required|string|max:255',
            'size_width' => 'required|integer',
            'size_height' => 'required|integer',
            'description' => 'required|string',
        ]);

        // Update the task with validated data
        $task->update([
            'campaign_id' => $validatedData['campaign_id'],
            'name' => $validatedData['name'],
            'date_required' => $validatedData['date_required'],
            'task_urgent' => $validatedData['task_urgent'] ?? 0,
            'category_id' => $validatedData['category_id'],
            'size_width' => $validatedData['size_width'],
            'size_height' => $validatedData['size_height'],
            'description' => $validatedData['description'],
        ]);

        // Redirect back with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $task = Tasks::find($id);
        // dd($task);
        $task->is_active = 0;
        $task->update();
        // $tasks->delete();
        return redirect()->route('tasks.index')->with('success', 'Task soft deleted successfully!');

    }
}
