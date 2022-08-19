<?php

namespace Tests\Unit;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class SaveUserBackgroundInformationListenerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    protected function tearDown(): void
    {
        // parent::tearDown();
    }

    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function it_should_create_details_of_the_user()
    {
        $user = User::factory()->create();
        $request = app(Request::class);
        $event = new UserSaved($user);

        $listener = new SaveUserBackgroundInformation(new UserService($user, $request));
        $listener->handle($event);

        $this->assertDatabaseHas('details', ['user_id' => $user->id]);
        $this->assertDatabaseCount('details', 8);
    }
}
