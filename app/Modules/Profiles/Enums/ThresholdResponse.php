<?php

namespace App\Modules\Profiles\Enums;

enum ThresholdResponse: string
{
    case HearClearly = 'hear_clearly';
    case BarelyHear  = 'barely_hear';
    case Feel        = 'feel';
    case Nothing     = 'nothing';
}
