<?php

namespace Tests\Feature;

use http\Env\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        Http::fake([
            'google.com/*' => Http::response('fake request', 200, ['Headers']),
        ]);

        $response = $this->post('/url/1/checks');
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('urls/1');
        $this->assertDatabaseHas('url_checks', ['id' => 1]);
    }
}
