<?php

namespace App\Http\Requests\Tasks;

use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStatusRequest extends FormRequest
{
    /** @var int[] */
    private array $taskStatuses;

    public function __construct(
        array $query = [], array $request = [], array $attributes = [], array $cookies = [],
        array $files = [], array $server = [], $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->taskStatuses = TaskStatus::all()
            ->pluck('id')
            ->toArray();
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'numeric',
                Rule::in($this->taskStatuses)
            ]
        ];
    }
}
