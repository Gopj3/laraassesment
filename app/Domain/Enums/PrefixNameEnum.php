<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum PrefixNameEnum: string
{
    case MRS = 'Mrs.';
    case MR = 'Mr.';
    case MS = 'Ms.';
}
