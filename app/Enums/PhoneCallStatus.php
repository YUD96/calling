<?php

namespace App\Enums;

enum PhoneCallStatus: string
{
    case WaitingReceiver = 'waiting_receiver';
    case Cancelled = 'canceled';
    case TalkStated = 'talk_started';
    case Finished = 'finished';
}
