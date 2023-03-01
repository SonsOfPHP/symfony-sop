<?php

declare(strict_types=1);

namespace Shared\Tests\Functional\Controller\Registration;

/**
 * @coversDefaultClass \Shared\Controller\Registration\RegisterAction
 */
final class RegisterActionTest extends \WebTestCase
{
    /**
     * @covers ::__invoke
     */
    public function testResponseIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
    }
}
