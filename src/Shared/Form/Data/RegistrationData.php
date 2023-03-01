<?php

declare(strict_types=1);

namespace Shared\Form\Data;

use Shared\Entity\User;
use Shared\Validator\UniqueDto;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueDto(fields: 'email', entityClass: User::class)]
class RegistrationData
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        minMessage: 'Your password must be at least {{ limit }} characters.',
        max: 4096,
        maxMessage: 'Who the fuck has a password this long?'
    )]
    public string $password;
}
