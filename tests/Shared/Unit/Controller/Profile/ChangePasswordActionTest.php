<?php

declare(strict_types=1);

namespace Shared\Tests\Unit\Controller\Profile;

use Shared\Controller\Profile\ChangePasswordAction;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @coversDefaultClass \Shared\Controller\Profile\ChangePasswordAction
 */
final class ChangePasswordActionTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $controller = new ChangePasswordAction(
            commandBus: $this->createMock(CommandMessageBus::class),
            passwordHasher: $this->createMock(UserPasswordHasherInterface::class),
            notifier: $this->createMock(NotifierInterface::class),
        );

        $this->assertInstanceOf(ServiceSubscriberInterface::class, $controller);
    }
}
