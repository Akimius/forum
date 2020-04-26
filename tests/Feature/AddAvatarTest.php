<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_add_avatars(): void
    {
        $this->postJson('api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided(): void
    {
        $this->withExceptionHandling()->signIn();

        $this->postJson('api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'string, not an image'
        ])->assertStatus(422); // Unprocessable entity
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_account(): void
    {
        $this->signIn();

        Storage::fake('public');

        $this->postJson('api/users/' . auth()->id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $this->assertEquals('avatars/' . $file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
