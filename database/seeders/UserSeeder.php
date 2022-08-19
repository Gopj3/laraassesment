<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(5)->create();

        // User to log in
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'LastName',
            'username' => 'admin_for_login',
            'email' => 'admin@admin.com',
            'password' => \Hash::make('12345678aA'),
        ]);
    }
}
