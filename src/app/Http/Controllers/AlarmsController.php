<?php

namespace App\Http\Controllers;

use App\Http\Requests\Alarms\PeriodicAlarmRequest;
use App\Http\Requests\Alarms\SingleAlarmRequest;
use App\Models\Alarm;
use App\Models\AlarmGroup;
use App\Services\AlarmsService;
use App\Utils\DateUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AlarmsController extends Controller
{
    private AlarmsService $alarmsService;

    public function __construct(AlarmsService $alarmsService)
    {
        $this->alarmsService = $alarmsService;
    }

    public function findAll()
    {
        $this->alarmsService->assignUser(Auth::user());
        return $this->alarmsService->getAll();
    }

    public function createSingle(SingleAlarmRequest $request): Alarm
    {
        $this->alarmsService->assignUser(Auth::user());
        return $this->alarmsService->createSingle($request->all());
    }

    public function createPeriodic(PeriodicAlarmRequest $request): AlarmGroup
    {
        $data = $request->all();
        $dates = DateUtils::getDatesTillNextMonthEnd(
            $data['interval'],
            $data['intervalType'],
            new Carbon($data['start']),
            $data['stop'] !== null ? new Carbon($data['stop']) : null
        );
        $this->alarmsService->assignUser(Auth::user());
        return $this->alarmsService->createPeriodic($data, $dates, null);
    }

    public function validateSingleAlarm(SingleAlarmRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }

    public function validatePeriodicAlarm(PeriodicAlarmRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }

    public function checkAlarm(string $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $alarm = $this->alarmsService->getSingleAlarm($id);
        if ($alarm === null) {
            return $response->setStatusCode(404);
        }
        $this->alarmsService->checkAlarm($alarm);
        return $alarm->group()
                ->first() ?? $alarm;
    }

    public function uncheckAlarm(string $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $alarm = $this->alarmsService->getSingleAlarm($id);
        if ($alarm === null) {
            return $response->setStatusCode(404);
        }
        $result = $this->alarmsService->uncheckAlarm($alarm);
        if ($result) {
            return $alarm->group()
                    ->first() ?? $alarm;
        }
        return $response->setStatusCode(204);
    }

    public function checkNotification(int $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $notification = $this->alarmsService->getOneNotification($id);
        if ($notification === null) {
            return $response->setStatusCode(404);
        }
        $this->alarmsService->checkNotification($notification);
        $alarm = $notification->alarm()
            ->first();
        return $alarm->group()
                ->first() ?? $alarm;
    }

    public function uncheckNotification(int $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $notification = $this->alarmsService->getOneNotification($id);
        if ($notification === null) {
            return $response->setStatusCode(404);
        }
        $result = $this->alarmsService->uncheckNotification($notification);
        if ($result) {
            $alarm = $notification->alarm()
                ->first();
            return $alarm->group()
                    ->first() ?? $alarm;
        }
        return $response->setStatusCode(204);
    }

    public function deleteSingleAlarm(string $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $alarm = $this->alarmsService->getSingleAlarm($id);
        if ($alarm === null) {
            return $response->setStatusCode(404);
        }
        $this->alarmsService->deleteSingleAlarm($alarm);
        return $response->setStatusCode(204);
    }

    public function deletePeriodicAlarm(string $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $alarm = $this->alarmsService->getPeriodicAlarm($id);
        if ($alarm === null) {
            return $response->setStatusCode(404);
        }
        $this->alarmsService->deleteSPeriodicAlarm($alarm);
        return $response->setStatusCode(204);
    }

    public function deleteNotification(int $id, Response $response)
    {
        $this->alarmsService->assignUser(Auth::user());
        $notification = $this->alarmsService->getOneNotification($id);
        if ($notification === null) {
            return $response->setStatusCode(404);
        }
        $alarm = $this->alarmsService->deleteNotification($notification);
        if (
            $alarm->notifications()
                ->count() === 0
        ) {
            $this->alarmsService->deleteSingleAlarm($alarm);
            return $response->setStatusCode(204);
        }
        return $alarm->group()
                ->first() ?? $alarm;
    }
}
