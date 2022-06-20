<?php

declare(strict_types=1);

$finder = Symfony\Component\Finder\Finder::create()
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,

        /*
         * CUSTOM
         */
        'final_class' => true,
        'final_public_method_for_abstract_class' => true,
        'method_chaining_indentation' => true,
        'native_function_invocation' => [
            'include' => ['@all'],
        ],
        'no_superfluous_phpdoc_tags' => false,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => false,
        ],
        'phpdoc_no_empty_return' => false,
        'phpdoc_separation' => true,
        'phpdoc_trim' => true,
        'return_assignment' => true,
        'yoda_style' => false,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => false,
    ])
    ->setFinder($finder);
