<?php

declare(strict_types=1);

namespace Shared\Application\Command;

use Shared\Infrastructure\Symfony\Repository\UserRepository;
use SonsOfPHP\Component\Cqrs\Command\CommandMessageHandlerInterface;

final class ChangeEmailHandler implements CommandMessageHandlerInterface
{
    public function __construct(
        private UserRepository $repository,
    ) {
    }

    public function __invoke(ChangeEmail $command): void
    {
        $entity = $this->repository->find($command->getOption('id'));
        $entity->setEmail($command->getOption('email'));

        $this->repository->saveAndFlush($entity);
    }
}
