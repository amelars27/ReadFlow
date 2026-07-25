<?php

namespace App\Enums;

enum ReadingStatus: string
{
    case NotStarted = 'Not Started';
    case Reading = 'Reading';
    case Completed = 'Completed';
}
