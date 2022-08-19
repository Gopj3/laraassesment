<?php

namespace Tests\Feature;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaveUserBackgroundInformationListenerTest extends TestCase
{

    use DatabaseMigrations, RefreshDatabase, WithFaker;

    protected function tearDown(): void
    {
        // parent::tearDown();
    }

    /**
     * A basic feature test example.
     * @test
     *
     * @return void
     */
    public function it_should_create_user_and_details_of_the_user()
    {
        $user = User::factory()->make();
        $email = $this->faker()->safeEmail();
        $username = $this->faker()->userName();

        $response = $this->actingAs($user)->post('/users', [
            'firstname' => 'Sally',
            'lastname' => 'Wally',
            'username' => $username,
            'email' => $email,
            'password' => 'Admin@2022Secret',
            'prefixname' => 'Mrs.',
            'suffixname' => 'suffixname',
            'middlename' => 'senior'
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseCount('details', 4);
        $response->assertStatus(200);
    }
}
