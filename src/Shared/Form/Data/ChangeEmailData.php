<?php

declare(strict_types=1);

namespace Shared\Form\Data;

use Shared\Entity\User;
use Shared\Validator\UniqueDto;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

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
