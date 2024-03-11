<?php

namespace App\Enums;

enum UserRole: string
{
    case SYSTEM = 'system';
    case ADMINISTRATOR = 'administrator';
    case TEACHER = 'teacher';
    case LEARNER = 'learner';
}
