<?php

declare(strict_types=1);

namespace Shared\Tests\Unit\Controller\Registration;

use Shared\Controller\Registration\RegisterAction;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @coversDefaultClass \Shared\Controller\Registration\RegisterAction
 */
final class RegisterActionTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $controller = new RegisterAction(
            commandBus: $this->createMock(CommandMessageBus::class),
            passwordHasher: $this->createMock(UserPasswordHasherInterface::class),
            notifier: $this->createMock(NotifierInterface::class),
        );

        $this->assertInstanceOf(ServiceSubscriberInterface::class, $controller);
    }
}
