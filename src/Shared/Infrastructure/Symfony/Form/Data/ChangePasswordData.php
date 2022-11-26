<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Form\Data;

use Shared\Infrastructure\Doctrine\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordData
{
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        minMessage: 'Your password must be at least {{ limit }} characters.',
        max: 4096,
        maxMessage: 'Who the fuck has a password this long?'
    )]
    public string $password;

    #[Assert\NotBlank]
    #[UserPassword]
    public string $currentPassword;
}
