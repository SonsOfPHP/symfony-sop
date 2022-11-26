<?php

declare(strict_types=1);

namespace Shared\Application\Command;

use Shared\Infrastructure\Symfony\Repository\UserRepository;
use SonsOfPHP\Component\Cqrs\Command\CommandMessageHandlerInterface;

final class ChangePasswordHandler implements CommandMessageHandlerInterface
{
    public function __construct(
        private UserRepository $repository,
    ) {
    }

    public function __invoke(ChangePassword $command): void
    {
        $entity = $this->repository->find($command->getOption('id'));
        $entity->setPassword($command->getOption('password'));

        $this->repository->saveAndFlush($entity);
    }
}
