<?php

namespace App\Modules\HearingTests\Enums;

enum SessionStatus: string
{
    case InProgress = 'in_progress';
    case Completed  = 'completed';
    case Abandoned  = 'abandoned';
}
