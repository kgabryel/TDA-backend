<?php

namespace App\Http\Requests\Alarms;

use App\Models\NotificationsType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SingleAlarmRequest extends FormRequest
{
    /** @var int[] */
    private array $notificationTypes;

    public function __construct(
        array $query = [], array $request = [], array $attributes = [], array $cookies = [],
        array $files = [], array $server = [], $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->notificationTypes = NotificationsType::all()
            ->pluck('id')
            ->toArray();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|filled|string|max:100',
            'content' => 'nullable|string',
            'notificationTypes' => 'required|array|min:1',
            'notificationTypes.*' => [Rule::in($this->notificationTypes)],
            'notifications' => 'required|array|min:1',
            'notifications.*'=>'date'
        ];
    }
}
