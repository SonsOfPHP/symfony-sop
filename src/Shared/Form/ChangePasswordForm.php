<?php

declare(strict_types=1);

namespace Shared\Form;

use Shared\Form\Data\ChangePasswordData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

final class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'required' => true,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'New Password',
                ],
                'second_options' => [
                    'label' => 'Confirm New Password',
                ],
                'invalid_message' => 'Passwords do not match',
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordData::class,
        ]);
    }
}
