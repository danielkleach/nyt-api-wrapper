<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BestSellerApiTest extends TestCase
{
    public function test_valid_request_returns_results()
    {
        Http::fake([
            '*' => Http::response(['results' => [['title' => 'Test Book']]], 200),
        ]);
        $response = $this->getJson('/api/v1/best-sellers?author=John');
        $response->assertStatus(200)
            ->assertJsonStructure(['results']);
    }

    public function test_validation_error_returns_422()
    {
        $response = $this->getJson('/api/v1/best-sellers?author[]=bad');
        $response->assertStatus(422);
    }

    public function test_nyt_api_error_returns_502()
    {
        Http::fake([
            '*' => Http::response(['fault' => 'error'], 500),
        ]);
        $response = $this->getJson('/api/v1/best-sellers?author=John');
        $response->assertStatus(500)->assertJson(['error' => 'NYT API error']);
    }

    public function test_offset_parameter_is_accepted()
    {
        Http::fake([
            '*' => function ($request) {
                parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);
                $this->assertEquals('20', $query['offset'] ?? null);

                return Http::response(['results' => []], 200);
            },
        ]);
        $response = $this->getJson('/api/v1/best-sellers?author=Diana%20Gabaldon&offset=20');
        $response->assertStatus(200)->assertJson(['results' => []]);
    }
}
