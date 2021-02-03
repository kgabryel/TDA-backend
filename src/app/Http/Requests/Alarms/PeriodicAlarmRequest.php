<?php

namespace App\Http\Requests\Alarms;

use App\Models\NotificationsType;
use App\Utils\DateUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PeriodicAlarmRequest extends FormRequest
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
            'title' => 'required|filled|string|max:100',
            'content' => 'nullable|string',
            'notificationTypes' => 'required|array|min:1',
            'notificationTypes.*' => [Rule::in($this->notificationTypes)],
            'notifications' => 'required|array|min:1',
            'notifications.*' => 'numeric|between:-86400,172799',
            'interval' => 'required|numeric|min:0',
            'intervalType' => [
                [
                    'required',
                    'string',
                    'min:0',
                    Rule::in($this->intervalTypes)
                ]
            ],
            'start' => 'required|date',
            'stop' => 'nullable|date|after:start'
        ];
    }
}
