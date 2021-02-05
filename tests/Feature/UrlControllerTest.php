<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var bool
     */
    protected $seed = true;

    public function testIndex(): void
    {
        $response = $this->get('/urls');
        $response->assertOk();
        $response->assertSeeText('https://google.com');
        $response->assertSeeText('https://yandex.ru');
    }

    /**
     * @return void
     */
    public function testShow(): void
    {
        $response = $this->get('/urls/1');
        $response->assertOk();
    }

    /**
     * @return void
     */
    public function testStore(): void
    {
        $url = 'https://hexlet.io';
        $requestData = [
            'urls' => [
                'name' => $url
            ]
        ];

        $response = $this->post('/urls', $requestData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('urls'));
        $this->assertDatabaseHas('urls', ['name' => $url]);
    }
}
