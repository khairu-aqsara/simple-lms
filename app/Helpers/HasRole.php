<?php

namespace App\Helpers;

use App\Enums\UserRole;
use Filament\Panel;

trait HasRole
{
    public function IsSystemAdministrator(): bool
    {
        return $this->role == UserRole::SYSTEM->value;
    }

    public function IsAdministrator(): bool
    {
        return $this->role == UserRole::ADMINISTRATOR->value;
    }

    public function IsTeacher(): bool
    {
        return $this->role == UserRole::TEACHER->value;
    }

    public function IsLearner(): bool
    {
        return $this->role == UserRole::LEARNER->value;
    }

    public function canAccessPanel(Panel $panel): bool {
        return $this->role == UserRole::SYSTEM->value;
    }
}
