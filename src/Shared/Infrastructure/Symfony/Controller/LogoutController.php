<?php

namespace Shared\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/logout', name: 'shared_logout')]
final class LogoutController extends AbstractController
{
    public function __invoke(): Response
    {
        throw new \Exception();
    }
}
