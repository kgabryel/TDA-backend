<?php

namespace App\Http\Requests\Tasks;

use App\Models\NotificationsType;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SingleTaskRequest extends FormRequest
{
    /** @var int[] */
    private array $notificationTypes;
    /** @var string[] */
    private array $mainTasks;

    public function __construct(
        array $query = [], array $request = [], array $attributes = [], array $cookies = [],
        array $files = [], array $server = [], $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->notificationTypes = NotificationsType::all()
            ->pluck('id')
            ->toArray();
        $this->mainTasks = Task::where('user_id', '=', Auth::user()->id)
            ->whereNull('parent_id')
            ->pluck('id')
            ->toArray();
    }

    public function rules(): array
    {
        return [
            'task' => 'required|array',
            'task.title' => 'required_with:task|filled|string|max:100',
            'task.content' => 'nullable|string',
            'task.date' => 'nullable|date',
            'task.mainTask' => [
                'nullable',
                Rule::in($this->mainTasks)
            ],
            'alarm' => 'nullable|array',
            'alarm.title' => 'required_with:alarm|filled|string|max:100',
            'alarm.content' => 'nullable|string',
            'alarm.notificationTypes' => 'required_with:alarm|array|min:1',
            'alarm.notificationTypes.*' => [Rule::in($this->notificationTypes)],
            'alarm.notifications' => 'required_with:alarm|array|min:1',
            'alarm.notifications.*' => 'required|date',
        ];
    }
}
