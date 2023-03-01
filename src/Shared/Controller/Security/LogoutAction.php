<?php

declare(strict_types=1);

namespace Shared\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logout', name: 'shared_logout')]
final class LogoutAction extends AbstractController
{
    public function __invoke(): Response
    {
        throw new \Exception();
    }
}
