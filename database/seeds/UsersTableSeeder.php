<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $defaultUsers = [
            ['name' => 'Akim',  'email' => 'akim.savchenko@gmail.com',  'email_verified_at' => now(), 'password' => Hash::make('1234')],
            ['name' => 'Klepa', 'email' => 'anna.klepickova@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('1234')],
        ];

        collect($defaultUsers)->each(
            function ($user) {
                try {
                    User::forceCreate($user);
                } catch (Exception $exception) {
                    $this->command->warn("Can NOT create user {$user['name']}. Already exists.");
                }
            }
        );


    }
}
