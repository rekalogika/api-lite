<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/tests/src')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],

    // imports
    'fully_qualified_strict_types' => true,
    'global_namespace_import' => [
        'import_classes' => false,
        'import_constants' => false,
        'import_functions' => false,
    ],
    'no_leading_import_slash' => true,
    'no_unneeded_import_alias' => true,
    'no_unused_imports' => true,
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'imports_order' => ['const', 'class', 'function']
    ],
    'single_line_after_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'declare_strict_types' => true,
    'header_comment' => [
            'header' => '',
        ]
    ])
    ->setFinder($finder)
;
