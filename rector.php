<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests/bin',
        __DIR__ . '/tests/config',
        __DIR__ . '/tests/public',
        __DIR__ . '/tests/src',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        strictBooleans: true,
        symfonyCodeQuality: true,
        doctrineCodeQuality: true,
    )
    ->withPhpSets(php82: true)
    ->withRules([
        AddOverrideAttributeToOverriddenMethodsRector::class,
    ])
    ->withSkip([
        // potential cognitive burden
        FlipTypeControlToUseExclusiveTypeRector::class,

        // results in too long variables
        CatchExceptionNameMatchingTypeRector::class,

        // makes code unreadable
        DisallowedShortTernaryRuleFixerRector::class,

        // unsafe
        SeparateMultiUseImportsRector::class,
    ]);
