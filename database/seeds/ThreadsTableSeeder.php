<?php

use App\Thread;
use App\Reply;
use App\User;
use Illuminate\Database\Seeder;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $threads = factory(Thread::class, 20)->create();

        $threads->each(
            static function ($thread) {
                factory(Reply::class, 3)
                    ->create(
                        ['thread_id' => $thread->id]
                    );
            }
        );

        $akimThread = factory(Thread::class, 1)->create(
            ['user_id' => User::whereEmail('akim.savchenko@gmail.com')->first()->id]
        );

        $akimThread->each(
            static function ($thread) {
                factory(Reply::class, 5)
                    ->create(
                        ['thread_id' => $thread->id]
                    );
            }
        );

        $annaThread = factory(Thread::class, 1)->create(
            ['user_id' => User::whereEmail('anna.klepickova@gmail.com')->first()->id]
        );

        $annaThread->each(
            static function ($thread) {
                factory(Reply::class, 4)
                    ->create(
                        ['thread_id' => $thread->id]
                    );
            }
        );
    }
}
