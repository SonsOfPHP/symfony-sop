<?php

declare(strict_types=1);

namespace Shared\Application\Command;

use Shared\Infrastructure\Doctrine\Entity\User;
use Shared\Infrastructure\Symfony\Repository\UserRepository;
use SonsOfPHP\Component\Cqrs\Command\CommandMessageHandlerInterface;

final class RegisterUserHandler implements CommandMessageHandlerInterface
{
    public function __construct(
        private UserRepository $repository,
    ) {
    }

    public function __invoke(RegisterUser $command): void
    {
        $entity = new User();
        $entity
            ->setEmail($command->getOption('email'))
            ->setPassword($command->getOption('password'))
        ;

        $this->repository->save($entity);
    }
}
