<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';

    case Start = 'start';

    case Bug = 'bug';

    case Finish = 'finish';

    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Start => 'Start',
            self::Bug => 'Bug',
            self::Finish => 'Finish',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::New => 'warning',
            self::Start => 'info',
            self::Finish => 'success',
            self::Cancelled , self::Bug => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New => 'heroicon-m-sparkles',
            self::Bug => 'heroicon-m-arrow-path',
            self::Start => 'heroicon-m-arrow-path',
            self::Finish => 'heroicon-m-truck',
            self::Cancelled => 'heroicon-m-x-circle',
        };
    }
}
