<?php
declare(strict_types=1);

namespace App\Services\Interfaces;

interface UserServiceInterface
{
    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key);
}
