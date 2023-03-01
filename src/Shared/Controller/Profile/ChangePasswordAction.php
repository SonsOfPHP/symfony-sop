<?php

declare(strict_types=1);

namespace Shared\Controller\Profile;

use Shared\Message\Command\ChangePassword;
use Shared\Entity\User;
use Shared\Form\Data\ChangePasswordData;
use Shared\Form\ChangePasswordForm;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/change-password', name: 'shared_profile_change_password')]
final class ChangePasswordAction extends AbstractController
{
    public function __construct(
        private CommandMessageBus $commandBus,
        private UserPasswordHasherInterface $passwordHasher,
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordData($this->getUser()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->commandBus->dispatch(new ChangePassword([
                'id'       => $this->getUser()->getId(),
                'password' => $this->passwordHasher->hashPassword($this->getUser(), $data->password),
            ]));

            $this->notifier->send((new Notification('Password Updated', ['browser']))->importance(Notification::IMPORTANCE_LOW));

            return $this->redirectToRoute('shared_profile_change_password');
        }

        return $this->renderForm('profile/change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
