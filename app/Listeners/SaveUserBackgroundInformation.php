<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserSaved;
use App\Models\Detail;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class SaveUserBackgroundInformation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\UserSaved $event
     *
     * @return void
     */
    public function handle(UserSaved $event): void
    {
        $this->userService->saveDetails($event->user);
    }
}
