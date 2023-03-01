<?php

declare(strict_types=1);

// @see https://github.com/symfony/symfony/issues/22592

namespace Shared\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueDtoValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $manager,
        private PropertyAccessorInterface $accessor,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDto) {
            throw new UnexpectedTypeException($constraint, UniqueDto::class);
        }

        if (!\is_array($constraint->fields) && !\is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if (null !== $constraint->errorPath && !\is_string($constraint->errorPath)) {
            throw new UnexpectedTypeException($constraint->errorPath, 'string or null');
        }

        if (!\is_string($constraint->entityClass)) {
            throw new UnexpectedTypeException($constraint->entityClass, 'string');
        }

        $fields = (array) $constraint->fields;

        if (0 === \count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        if (null === $value) {
            return;
        }

        if (!\is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        $criteria = [];
        foreach ($fields as $fieldName) {
            $criteria[$fieldName] = $this->accessor->getValue($value, $fieldName);
        }

        $repository = $this->manager->getRepository($constraint->entityClass);

        if (0 === $repository->count($criteria)) {
            return;
        }

        $errorPath = $constraint->errorPath ?? $fields[0];

        $this->context->buildViolation($constraint->message)
            ->atPath($errorPath)
            ->addViolation();
    }
}
