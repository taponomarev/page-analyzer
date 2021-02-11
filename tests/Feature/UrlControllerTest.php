<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        $response->assertSeeText('https://google.com');
    }

    /**
     * @return void
     */
    public function testStoreOk(): void
    {
        $url = 'https://hexlet.io';
        $requestData = [
            'url' => [
                'name' => $url
            ]
        ];

        $response = $this->post('/urls', $requestData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/urls');
        $this->assertDatabaseHas('urls', ['name' => $url]);
    }

    public function testStoreError(): void
    {
        $url = '';
        $requestData = [
            'url' => [
                'name' => $url
            ]
        ];

        $response = $this->post('/urls', $requestData);
        $response->assertSessionHasErrorsIn('The url.name field is required.');
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('urls', ['name' => $url]);
    }

    public function testCheckOk(): void
    {
        $fakeHtml = <<<'TAG'
            <meta name="description" content="Test description">
            <meta name="keywords" content="keyword1, keyword2">
            <h1>Test h1</h1>
        TAG;
        Http::fake([
            'google.com/*' => Http::response($fakeHtml, 200, ['Headers']),
        ]);

        $response = $this->post('/url/1/checks');
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('urls/1');
        $this->assertDatabaseHas('url_checks', [
            'id' => 1,
            'status_code' => 200,
            'h1' => 'Test h1',
            'description' => 'Test description',
            'keywords' => 'keyword1, keyword2'
        ]);
    }

    public function testCheckError(): void
    {
        $fakeHtml = '';
        Http::fake([
            'google.com/*' => Http::response($fakeHtml, 200, ['Headers']),
        ]);

        $response = $this->post('/url/1/checks');
        $response->assertSeeText('The site not available');
        $this->assertDatabaseMissing('url_checks', [
            'id' => 1,
            'status_code' => 404
        ]);
    }
}
