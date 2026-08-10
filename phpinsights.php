<?php

declare(strict_types=1);

return [
    'preset' => 'default',
    'ide' => 'phpstorm',
    'exclude' => [
        'src/views',
        'src/Core/Helpers.php',
    ],
    'add' => [],
    'remove' => [
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits::class,
        \NunoMaduro\PhpInsights\Domain\Sniffs\Architecture\ForbiddenSettersSniff::class,
        \SlevomatCodingStandard\Sniffs\ControlStructures\DisallowShortTernaryOperatorSniff::class,
        \SlevomatCodingStandard\Sniffs\PHP\DisallowShortTernaryOperatorSniff::class,
        \PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff::class,
        \SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff::class,
    ],
    'config' => [],
];
