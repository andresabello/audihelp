<?php

namespace App\Modules\HearingTests\Enums;

enum PatternType: string
{
    case Tone  = 'tone';
    case Pulse = 'pulse';
    case Am    = 'am';   // amplitude-modulated
}
