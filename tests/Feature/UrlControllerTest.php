<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testIndex()
    {
        $response = $this->get(route('urls'));
        $response->assertOk();
        $this->assertDatabaseHas('urls', ['name' => 'https://google.com']);
        $this->assertDatabaseHas('urls', ['name' => 'https://yandex.ru']);
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', 1));
        $response->assertOk();
    }

    public function testStore()
    {
        $url = 'https://hexlet.io';
        $requestData = [
            'urls' => [
                'name' => $url
            ]
        ];

        $response = $this->post(route('urls.store'), $requestData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('urls'));
        $this->assertDatabaseHas('urls', ['name' => $url]);
    }
}
