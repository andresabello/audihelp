<?php

namespace App\Modules\HearingTests\Enums;

enum TrialResponse: string
{
    case HearClearly = 'hear_clearly';
    case BarelyHear  = 'barely_hear';
    case Feel        = 'feel';
    case Nothing     = 'nothing';
}
