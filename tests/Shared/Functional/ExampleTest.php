<?php

declare(strict_types=1);

namespace Shared\Tests\Functional;

final class ExampleTest extends \WebTestCase
{
    public function testApp(): void
    {
        $client = static::createClient([
            'name' => 'app',
        ]);
        $client->request('GET', '/');
        $this->assertResponseRedirects();
    }

    public function testApi(): void
    {
        $client = static::createClient([
            'name' => 'api',
        ]);
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testAdmin(): void
    {
        $client = static::createClient([
            'name' => 'admin',
        ]);
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(404);
    }
}
