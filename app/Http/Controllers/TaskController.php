<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use App\Responses\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return ApiResponse
     */
    public function index(): ApiResponse
    {
        try {
            $tasks = Task::all();

            return new ApiResponse(status: 200, data: new TaskCollection($tasks));
        } catch (\Exception $exception) {

            return new ApiResponse(status: 500, message: $exception->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     * @param CreateTaskRequest $request
     * @return ApiResponse
     */
    public function create(CreateTaskRequest $request): ApiResponse
    {
        DB::beginTransaction();

        try {
            $task = Task::query()->create(Arr::add($request->validated(), 'user_id', auth()->id()));

            if ($file = $request->file('file'))
                $task->addMedia($file)->toMediaCollection('file');

            DB::commit();

            return new ApiResponse(status: 201, message: 'Task created');
        } catch (\Exception $exception) {
            DB::rollBack();

            return new ApiResponse(status: 500, message: $exception->getMessage());
        }
    }


    /**
     * Display the specified resource.
     * @param int $id
     * @return ApiResponse
     */
    public function show(int $id): ApiResponse
    {
        try {
            $task = Task::query()->find($id);

            return ($task)
                ? new ApiResponse(status: 200, data: new TaskResource($task))
                : new ApiResponse(404, message: 'Task not found');
        } catch (\Exception $exception) {

            return new ApiResponse(status: 500, message: $exception->getMessage());
        }

    }


    /**
     * Update the specified resource in storage.
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function update(UpdateTaskRequest $request, int $id): ApiResponse
    {
        DB::beginTransaction();

        try {
            $task = Task::query()->find($id);

            $task->update($request->validated());

            if ($file = $request->file('file'))
                $task->addMedia($file)->toMediaCollection('file');

            DB::commit();

            return new ApiResponse(status: 200, data: new TaskResource($task));

        } catch (\Exception $exception) {
            DB::rollBack();

            return new ApiResponse(status: 500, message: $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return ApiResponse
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
