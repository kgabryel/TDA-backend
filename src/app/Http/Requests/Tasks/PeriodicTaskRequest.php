<?php

namespace App\Http\Requests\Tasks;

use App\Models\NotificationsType;
use App\Utils\DateUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PeriodicTaskRequest extends FormRequest
{
    /** @var int[] */
    private array $notificationTypes;
    /** @var string[]  */
    private array $intervalTypes;

    public function __construct(
        array $query = [], array $request = [], array $attributes = [], array $cookies = [],
        array $files = [], array $server = [], $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->notificationTypes = NotificationsType::all()
            ->pluck('id')
            ->toArray();
        $this->intervalTypes = DateUtils::getAvailableIntervalTypes();
    }

    public function rules(): array
    {
        return [
            'task' => 'required|array',
            'task.title' => 'required_with:task|filled|string|max:100',
            'task.content' => 'nullable|string',
            'task.interval' => 'required|numeric|min:0',
            'task.intervalType' => [
                [
                    'required',
                    'string',
                    'min:0',
                    Rule::in($this->intervalTypes)
                ]
            ],
            'task.start' => 'required|date',
            'task.stop' => 'nullable|date|after:start',
            'alarm' => 'nullable|array',
            'alarm.title' => 'required_with:alarm|filled|string|max:100',
            'alarm.content' => 'nullable|string',
            'alarm.notificationTypes' => 'required_with:alarm|array|min:1',
            'alarm.notificationTypes.*' => [Rule::in($this->notificationTypes)],
            'alarm.notifications' => 'required_with:alarm|array|min:1',
            'notifications.*' => 'numeric|between:-86400,172799',
        ];
    }
}
