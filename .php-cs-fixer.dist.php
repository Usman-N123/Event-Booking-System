<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
        __DIR__ . '/resources',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        'indentation_type' => true,
        'array_indentation' => true,
        'method_chaining_indentation' => true,
    ])
    ->setIndent("  ")
    ->setLineEnding("\n")
    ->setFinder($finder);
