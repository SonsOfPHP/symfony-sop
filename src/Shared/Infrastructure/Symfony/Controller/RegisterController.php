<?php

namespace Shared\Infrastructure\Symfony\Controller;

use Shared\Application\Command\RegisterUser;
use Shared\Infrastructure\Doctrine\Entity\User;
use Shared\Infrastructure\Symfony\Form\Data\RegistrationData;
use Shared\Infrastructure\Symfony\Form\RegisterForm;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'shared_register')]
final class RegisterController extends AbstractController
{
    public function __construct(
        private CommandMessageBus $commandBus,
        private UserPasswordHasherInterface $passwordHasher,
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(RegisterForm::class, new RegistrationData());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = new User();
            $plaintextPassword = $data->password;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);

            $this->commandBus->dispatch(new RegisterUser([
                // 'account_id' => Uuid::v6(),
                // 'id'         => Uuid::v6(),
                'email' => $data->email,
                'password' => $hashedPassword,
            ]));

            $this->notifier->send((new Notification('Account Registered, please log in', ['browser']))->importance(Notification::IMPORTANCE_LOW));

            return $this->redirectToRoute('shared_login');
        }

        return $this->renderForm('register.html.twig', [
            'form' => $form,
        ]);
    }
}
