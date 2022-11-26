<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Form\Data;

use Shared\Infrastructure\Doctrine\Entity\User;
use Shared\Infrastructure\Symfony\Validator\UniqueDto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

#[UniqueDto(fields: 'email', entityClass: User::class)]
class ChangeEmailData
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    public string $email;

    #[Assert\NotBlank]
    #[UserPassword]
    public string $password;

    public function __construct(User $entity)
    {
        $this->email = $entity->getEmail();
    }
}
