<?php

declare(strict_types=1);

namespace Shared\Message\Command;

use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegisterUser extends AbstractOptionsResolverCommandMessage
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('email')
            ->required()
            ->allowedTypes('string');

        $resolver->define('password')
            ->info('Hashed User Password')
            ->required()
            ->allowedTypes('string');
    }
}
