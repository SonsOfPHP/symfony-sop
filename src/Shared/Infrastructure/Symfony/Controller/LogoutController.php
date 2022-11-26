<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logout', name: 'shared_logout')]
final class LogoutController extends AbstractController
{
    public function __invoke(): Response
    {
        throw new \Exception();
    }
}
