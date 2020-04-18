<?php

namespace Tests\Unit;

use App\Inspections\InvalidKeyWords;
use App\Inspections\KeyHeldDown;
use App\Inspections\Spam;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    private Spam $spam;

    public function setUp(): void
    {
        parent::setUp();

        //$this->spam = new Spam();
    }

    /**
     * @test
     */
    public function it_checks_for_invalid_key_words(): void
    {
        $invalidKeyWord = new InvalidKeyWords();

        $this->assertFalse($invalidKeyWord->detect('Innocent reply here'));

        $this->expectException(\Exception::class);
        $invalidKeyWord->detect('yahoo customer support');
    }

    /**
     * @test
     */
    public function it_checks_for_any_key_held_down(): void
    {
        $keyHeldDown = new KeyHeldDown();

        $this->expectException(\Exception::class);

        $keyHeldDown->detect('Hello World aaaaaaaaaaaaaaaaaaaaaaa');
    }
}
