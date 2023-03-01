<?php

declare(strict_types=1);

namespace Shared\Controller\Profile;

use Shared\Message\Command\ChangeEmail;
use Shared\Entity\User;
use Shared\Form\Data\ChangeEmailData;
use Shared\Form\ChangeEmailForm;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/change-email', name: 'shared_profile_change_email')]
final class ChangeEmailAction extends AbstractController
{
    public function __construct(
        private CommandMessageBus $commandBus,
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ChangeEmailForm::class, new ChangeEmailData($this->getUser()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->commandBus->dispatch(new ChangeEmail([
                'id'    => $this->getUser()->getId(),
                'email' => $data->email,
            ]));

            $this->notifier->send((new Notification('Email Updated', ['browser']))->importance(Notification::IMPORTANCE_LOW));

            return $this->redirectToRoute('shared_profile_change_email');
        }

        return $this->renderForm('profile/change_email.html.twig', [
            'form' => $form,
        ]);
    }
}
