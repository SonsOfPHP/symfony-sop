<?php

declare(strict_types=1);

namespace Shared\Tests\Unit\Controller\Profile;

use Shared\Controller\Profile\ChangeEmailAction;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use Symfony\Component\Notifier\NotifierInterface;

/**
 * @coversDefaultClass \Shared\Controller\Profile\ChangeEmailAction
 */
final class ChangeEmailActionTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $controller = new ChangeEmailAction(
            commandBus: $this->createMock(CommandMessageBus::class),
            notifier: $this->createMock(NotifierInterface::class),
        );

        $this->assertInstanceOf(ServiceSubscriberInterface::class, $controller);
    }
}
