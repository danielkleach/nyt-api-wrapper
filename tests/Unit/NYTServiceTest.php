<?php

namespace Tests\Unit;

use App\Services\NYTService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NYTServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_successful_response()
    {
        Http::fake([
            '*' => Http::response(['results' => ['foo' => 'bar']], 200),
        ]);
        $service = new NYTService;
        $result = $service->getBestSellers(['author' => 'test']);
        $this->assertArrayHasKey('results', $result);
        $this->assertEquals(['foo' => 'bar'], $result['results']);
    }

    public function test_error_response()
    {
        Http::fake([
            '*' => Http::response(['fault' => 'error'], 400),
        ]);
        $service = new NYTService;
        $result = $service->getBestSellers(['author' => 'test']);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('NYT API error', $result['error']);
        $this->assertEquals(400, $result['status']);
    }

    public function test_exception_handling()
    {
        Http::fake([
            '*' => fn () => throw new \Exception('Connection error'),
        ]);
        $service = new NYTService;
        $result = $service->getBestSellers(['author' => 'test']);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('NYT API exception', $result['error']);
        $this->assertEquals('Connection error', $result['message']);
    }
}
