<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Tasks\AddTaskRequest;
use App\Http\Requests\Tasks\DeleteTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $tasks = Task::select('id', 'title', 'status', 'created_at', 'updated_at')
            ->where('user_id', '=', auth()->id())
            ->paginate($perPage);

        return $this->apiResponse(200, "products", null, [
            'products' => TaskResource::collection($tasks),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl(),
            ],
        ]);
    }

    /*---------------------------------------------------------------------------------------------*/

    public function store(AddTaskRequest $request)
    {
        $task = Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'status' => $request->status
        ]);

        return $this->apiResponse(201, "Task created", null, new TaskResource($task));
    }

    /*---------------------------------------------------------------------------------------------*/

    public function update(UpdateTaskRequest $request)
    {
        $task = Task::findOrFail($request->task_id);

        $task->update([
            'title' => $request->title,
            'status' => $request->status
        ]);

        return $this->apiResponse(200, "Task updated", null, new TaskResource($task));
    }

    /*---------------------------------------------------------------------------------------------*/

    public function destroy(DeleteTaskRequest $request)
    {
        Task::findOrFail($request->task_id)->delete();

        return $this->apiResponse(200, "Task deleted");
    }
}
