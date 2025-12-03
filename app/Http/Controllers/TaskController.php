<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Responses\ApiResponse;
use Illuminate\Support\Arr;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ApiResponse
    {
        try {
            $tasks = Task::all();

            return new ApiResponse(status: 200, data: $tasks);
        } catch (\Exception $exception) {
            return new ApiResponse(status: 500, message: $exception->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateTaskRequest $request): ApiResponse
    {
        try {
            Task::query()->create(Arr::add($request->validated(), 'user_id', auth()->id()));

            return new ApiResponse(status: 201, message: 'Task created');
        } catch (\Exception $exception) {
            return new ApiResponse(status: 500, message: $exception->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id): ApiResponse
    {
        try {
            $task = Task::query()->find($id);

            return ($task)
                ? new ApiResponse(status: 200, data: $task)
                : new ApiResponse(404, message: 'Task not found');
        } catch (\Exception $exception) {
            return new ApiResponse(status: 500, message: $exception->getMessage());
        }

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $id): ApiResponse
    {
        try {
            $task = Task::query()->find($id);

            $task->update($request->validated());

            return new ApiResponse(status: 200, data: $task);

        } catch (\Exception $exception) {
            return new ApiResponse(status: 500, message: $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): ApiResponse
    {
        try {
            $task = Task::query()->find($id);

            if (!$task)
                return new ApiResponse(status: 404, message: 'Task not found');

            $task->delete();

            return new ApiResponse(status: 200, message: 'Task deleted');

        } catch (\Exception $exception) {
            return new ApiResponse(status: 500, message: $exception->getMessage());
        }
    }
}
