<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Planned = 'planned';
    case InProgress = 'in_progress';
    case Done = 'done';
}
