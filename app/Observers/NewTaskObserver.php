<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class NewTaskObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        mail(
            to: $task->user?->email,
            subject: 'Вами была создана новая Задача №' . $task->id,
            message: 'Вами была создана задача №' . $task->id . ' «' . $task->title . '».',
        );
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
