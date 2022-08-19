<?php

namespace Tests\Unit;

use App\Models\User;
use Artisan;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Schema;
use Tests\TestCase;

class UserTableMigrationTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    protected function tearDown(): void
    {
        // parent::tearDown();
    }

    /**
     * A basic unit test example.
     *
     * @test
     *
     * @return void
     */
    public function users_columns_should_exists()
    {
        $actualColumns = Schema::getColumnListing('users');

        $this->assertContains('id', $actualColumns);
        $this->assertContains('firstname', $actualColumns);
        $this->assertContains('lastname', $actualColumns);
        $this->assertContains('username', $actualColumns);
        $this->assertContains('prefixname', $actualColumns);
        $this->assertContains('suffixname', $actualColumns);
        $this->assertContains('middlename', $actualColumns);
        $this->assertContains('password', $actualColumns);
        $this->assertContains('deleted_at', $actualColumns);
        $this->assertContains('created_at', $actualColumns);
        $this->assertContains('updated_at', $actualColumns);
        $this->assertContains('type', $actualColumns);
        $this->assertContains('photo', $actualColumns);
    }

    /**
     * @test
     *
     * @return void
     */
    public function users_table_should_have_some_default_values()
    {
        User::create(['firstname' => 'test', 'lastname' => 'test', 'email' => 'test@example.com', 'password' => 'test', 'username' => 'test']);
        $user = User::first();
        $this->assertEquals('user', $user->type);
    }

    /**
     * @test
     * @return void
     */
    public function users_table_should_contain_unique_emails()
    {
        $this->expectException(QueryException::class);

        User::create(['firstname' => 'test', 'lastname' => 'test', 'email' => 'test@example.com', 'password' => 'test', 'username' => 'test12']);
        User::create(['firstname' => 'test', 'lastname' => 'test', 'email' => 'test@example.com', 'password' => 'test', 'username' => 'test']);
    }

    /**
     * @test
     * @return void
     */
    public function users_table_should_contain_unique_usernames()
    {
        $this->expectException(QueryException::class);

        User::create(['firstname' => 'test', 'lastname' => 'test', 'email' => 'test2@example.com', 'password' => 'test', 'username' => 'test']);
        User::create(['firstname' => 'test', 'lastname' => 'test', 'email' => 'test@example.com', 'password' => 'test', 'username' => 'test']);
    }

    /**
     * @test
     * @return void
     */
    public function users_table_firstname_is_not_null()
    {
        $this->expectException(QueryException::class);

        User::create(['firstname' => null, 'lastname' => 'test', 'email' => 'test@example.com', 'password' => 'test', 'username' => 'test']);
    }

    /**
     * @test
     * @return void
     */
    public function users_table_lastname_is_not_null()
    {
        $this->expectException(QueryException::class);

        User::create(['firstname' => 'test', 'lastname' => null, 'email' => 'test@example.com', 'password' => 'test', 'username' => 'test']);
    }

    /**
     * @test
     * @return void
     */
    public function users_table_username_is_not_null()
    {
        $this->expectException(QueryException::class);

        User::create(['firstname' => 'test', 'lastname' => 'test', 'email' => 'test@example.com', 'password' => 'test', 'username' =>null]);
    }
}
