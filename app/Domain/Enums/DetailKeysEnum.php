<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum DetailKeysEnum: string
{
    case FullName = 'Full Name';
    case MiddleInitial = 'Middle Initial';
    case Avatar = 'Avatar';
    case Gender = 'Gender';
}
