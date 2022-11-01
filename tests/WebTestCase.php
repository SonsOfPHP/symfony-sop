<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        if (null === static::$class) {
            static::$class = static::getKernelClass();
        }

        $env   = $options['environment'] ?? 'test';
        $debug = $options['debug'] ?? true;
        $app   = $options['name'] ?? 'app';

        return new static::$class($env, (bool) $debug, $app);
    }
}
