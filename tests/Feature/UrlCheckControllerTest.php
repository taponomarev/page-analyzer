<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlCheckControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var bool
     */
    protected $seed = true;

    public function testStore(): void
    {
        $response = $this->post('/url/1/checks');
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('urls/1');
        $this->assertDatabaseHas('url_checks', ['id' => 1]);
    }
}
