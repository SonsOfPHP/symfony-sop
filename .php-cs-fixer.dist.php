<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src/')
    ->in('tests/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PHP81Migration' => true,
        '@Symfony' => true,
        // This is disabled because we have some @psalm-* tags, it's easier
        // to disable all this than to add each @psalm-* to be ignored
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;
