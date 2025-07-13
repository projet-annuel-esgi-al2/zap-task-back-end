<?php

$header = <<<EOF
Author: Marc Malha
Version: 1.0
EOF;

return (new PhpCsFixer\Config)
    ->setRules([
        '@PSR12' => true,
        'header_comment' => [
            'header' => $header,
            'location' => 'after_open',
            'comment_type' => 'PHPDoc',
        ],
    ])
    ->setFinder(PhpCsFixer\Finder::create()->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
    ]));
