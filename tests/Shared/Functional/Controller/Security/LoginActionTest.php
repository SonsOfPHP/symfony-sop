<?php

declare(strict_types=1);

namespace Shared\Tests\Functional\Controller\Security;

/**
 * @coversDefaultClass \Shared\Controller\Security\LoginAction
 */
final class LoginActionTest extends \WebTestCase
{
    /**
     * @covers ::__invoke
     */
    public function testResponseIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }
}
