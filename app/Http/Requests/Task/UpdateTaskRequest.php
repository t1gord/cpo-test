<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string|min:3|max:100',
            'description' => 'string',
            'status' => [
                'string',
                'in' => Rule::enum(TaskStatusEnum::class),
            ],
            'completion_date' => 'date',
        ];
    }
}
