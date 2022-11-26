<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src/')
    ->in('tests/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PHP80Migration:risky'     => true,
        '@PHP82Migration'           => true,
        '@Symfony'                  => true,
        '@Symfony:risky'            => true,
        '@PHPUnit84Migration:risky' => true,

        // This is disabled because we have some @psalm-* tags, it's easier
        // to disable all this than to add each @psalm-* to be ignored
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;
