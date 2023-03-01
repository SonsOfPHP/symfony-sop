<?php

declare(strict_types=1);

namespace Shared\Tests\Unit\Controller\Security;

use Shared\Controller\Security\LoginAction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * @coversDefaultClass \Shared\Controller\Security\LoginAction
 */
final class LoginActionTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $controller = new LoginAction(
            authenticationUtils: $this->createMock(AuthenticationUtils::class),
        );

        $this->assertInstanceOf(ServiceSubscriberInterface::class, $controller);
    }
}
