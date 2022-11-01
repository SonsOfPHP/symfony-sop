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

        if (isset($options['environment'])) {
            $env = $options['environment'];
        } elseif (isset($_ENV['APP_ENV'])) {
            $env = $_ENV['APP_ENV'];
        } elseif (isset($_SERVER['APP_ENV'])) {
            $env = $_SERVER['APP_ENV'];
        } else {
            $env = 'test';
        }

        if (isset($options['debug'])) {
            $debug = $options['debug'];
        } elseif (isset($_ENV['APP_DEBUG'])) {
            $debug = $_ENV['APP_DEBUG'];
        } elseif (isset($_SERVER['APP_DEBUG'])) {
            $debug = $_SERVER['APP_DEBUG'];
        } else {
            $debug = true;
        }

        if (isset($options['name'])) {
            $name = $options['name'];
        } elseif (isset($_ENV['APP_NAME'])) {
            $name = $_ENV['APP_NAME'];
        } elseif (isset($_SERVER['APP_NAME'])) {
            $name = $_SERVER['APP_NAME'];
        } else {
            $name = 'app';
        }

        return new static::$class($env, (bool) $debug, $name);
    }
}
