<?php

declare(strict_types=1);

// @see https://github.com/symfony/symfony/issues/22592

namespace Shared\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class UniqueDto extends Constraint
{
    public function __construct(
        public array|string $fields,
        public string $entityClass,
        public string $message = 'This value is already used.',
        public ?string $errorPath = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
