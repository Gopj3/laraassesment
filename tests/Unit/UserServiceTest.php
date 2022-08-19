<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    protected function tearDown(): void
    {
//        parent::tearDown();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        $model = new User();
        User::factory()->count(10)->create();
        $userService = new UserService($model, new Request());
        $paginated = $userService->list();
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginated);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        $arguments = [
            'firstname' => 'test',
            'lastname' => 'test lastname',
            'username' => Str::random(10),
            'email' => Str::random(5) . '@example.com',
            'password' => Hash::make(Str::random(7))
        ];

        $user = User::make($arguments);
        $userService = new UserService($user, new Request());
        $userFromService = $userService->store($arguments);
        $this->assertEquals($user->email, $userFromService->email);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        $userSpy = $this->spy('App\Models\User');
        $user = User::factory()->create();

        $userSpy->shouldReceive('findOrFail')->once()->andReturn($user);
        $userService = new UserService(app(User::class), app(Request::class));
        $finalUser = $userService->find(1);
        $this->assertEquals($user->id, $finalUser->id);
        $userSpy->shouldHaveReceived('findOrFail')->once();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        $user = User::factory()->create();
        $curEmail = $user->email;
        $attributes = ['email' => $this->faker()->safeEmail()];

        $userService = new UserService(app(User::class), app(Request::class));
        $userService->update($user, $attributes);

        $this->assertNotEquals($curEmail, $user->email);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        $user = User::factory()->create();

        $userService = new UserService(app(User::class), app(Request::class));
        $userService->delete($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        User::factory()->count(10)->create(['deleted_at' => new \DateTime('now')]);
        $userService = new UserService(app(User::class), app(Request::class));
        $paginated = $userService->listTrashed();
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginated);
        $this->assertCount(10, $paginated);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        $user = User::factory()->create(['deleted_at' => new \DateTime('now')]);

        $userService = new UserService($user, app(Request::class));
        $userService->restore($user->id);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        $user = User::factory()->create();

        $userService = new UserService(app(User::class), app(Request::class));
        $userService->destroy($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // TODO work on this
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test.jpeg', 300, 300)->size(100);

        $userService = new UserService(app(User::class), app(Request::class));
        $path = $userService->upload($file);

        Storage::disk('local')->assertExists($path);
    }
}
