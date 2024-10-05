<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Database\Eloquent\Collection|JsonResponse
     */
    public function index()
    {
        try {
            $tasks = Task::with(['category' => function($query) {
                $query->where('created_by', auth()->user()->id);
            }])
            ->where('created_by', auth()->user()->id)
            ->get();
            return $this->successResponse('Tasks retrieved successfully.', $tasks);
        } catch (\Exception $e) {
            Log::error('Error retrieving tasks: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'category_id' => 'required|exists:categories,id',
            ]);

            // Create the task with validated data
            $postData = $request->only(['title', 'description', 'due_date', 'category_id']);
            $postData['created_by'] = auth()->user()->id; // Assuming you want to track who created the task
            $task = Task::create($postData);

            // Log the creation of the new task
            Log::info('Task created successfully.', ['task_id' => $task->id, 'title' => $task->title]);

            // Return the created task
            return $this->successResponse('Task created successfully.', $task, 201); // Respond with 201 Created status
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->validator);            
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the task by ID, or return 404 if not found
            $task = Task::findOrFail($id);

            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'category_id' => 'required|exists:categories,id',
                'is_completed' => 'required|boolean',
            ]);

            // Update the task with validated data
            $task->update($request->only(['title', 'description', 'due_date', 'category_id','is_completed']));

            // Log the update action
            Log::info('Task updated successfully.', ['task_id' => $task->id, 'title' => $task->title]);

            // Return the updated task
            return $this->successResponse('Task updated successfully.', $task); // Respond with 200 OK status
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->validator);            
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the task by ID, or return 404 if not found
            $task = Task::findOrFail($id);

            // Delete the task
            $task->delete();

            // Log the deletion
            Log::info('Task deleted successfully.', ['task_id' => $task->id, 'title' => $task->title]);

            // Respond with a 204 No Content status
            return $this->successResponse('Task deleted successfully.', null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }
}
