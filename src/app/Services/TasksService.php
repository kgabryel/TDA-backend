<?php

namespace App\Services;

namespace App\Services;

use App\Factories\TaskFactory;
use App\Factories\TaskGroupFactory;
use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\TaskStatus;
use App\Models\User;
use Carbon\Carbon;

class TasksService
{
    private User $user;

    public function assignUser(User $user): void
    {
        $this->user = $user;
    }

    public function getAll()
    {
        $tasks = Task::where('user_id', '=', $this->user->id)
            ->whereNull('group_id')
            ->get();
        return $tasks->concat(
            TaskGroup::where('user_id', '=', $this->user->id)
                ->get()
        )
            ->sort(
                function($a, $b) {
                    if ($a->created_at == $b->created_at) {
                        return 0;
                    }
                    return ($a->created_at < $b->created_at) ? -1 : 1;
                }
            )
            ->values();
    }

    public function createSingle(array $data, ?Task $mainTask): Task
    {
        $date = null;
        if ($data['date'] !== null) {
            $date = new Carbon($data['date']);
        }
        return TaskFactory::create(
            $data,
            $this->user,
            $mainTask,
            null,
            $date
        );
    }

    /**
     * @param array $data
     * @param Carbon[] $dates
     *
     * @return TaskGroup
     */
    public function createPeriodic(array $data, array $dates): TaskGroup
    {
        $taskGroup = TaskGroupFactory::create($data, $this->user);
        foreach ($dates as $date) {
            TaskFactory::create($data, $this->user, null, $taskGroup, $date);
        }
        return $taskGroup;
    }

    public function getSingle(string $id): ?Task
    {
        return Task::where('user_id', '=', $this->user->id)
            ->where('id', '=', $id)
            ->first();
    }

    public function getPeriodic(string $id): ?TaskGroup
    {
        return TaskGroup::where('user_id', '=', $this->user->id)
            ->where('id', '=', $id)
            ->first();
    }

    public function changeStatus(Task $task, int $status, AlarmsService $alarmsService): Task
    {
        $task->status()
            ->associate(TaskStatus::find($status));
        if ($status === TaskStatus::TASK_DONE_ID) {
            $alarm = $task->alarm()
                ->first();
            if ($alarm !== null) {
                $alarmsService->assignUser($this->user);
                $alarmsService->checkAlarm($alarm);
            }
        }
        $task->save();
        return $task;
    }

    public function deleteSingle(Task $task): void
    {
        $task->delete();
    }

    public function deleteSPeriodic(TaskGroup $task): void
    {
        $task->delete();
    }
}
