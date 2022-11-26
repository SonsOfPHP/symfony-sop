<?php

declare(strict_types=1);

namespace Shared\Application\Command;

use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Uid\Uuid;

final class ChangePassword extends AbstractOptionsResolverCommandMessage
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        /**
         * @psalm-suppress UnusedClosureParam
         */
        $resolver->define('id')->required()
            ->allowedTypes('string', Uuid::class)
            ->normalize(fn (Options $options, string|Uuid $value) => (string) $value)
            ->allowedValues(function (string|Uuid $value) {
                if ($value instanceof Uuid) {
                    return true;
                }

                return Uuid::isValid($value);
            });

        $resolver->define('password')->required()->allowedTypes('string');
    }
}
