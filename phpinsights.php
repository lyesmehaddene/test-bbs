<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff;

return [
    'preset' => 'laravel',
    'exclude' => [
        'database/*',
    ],
    'remove' => [
        AssignmentInConditionSniff::class,
        ForbiddenPublicPropertySniff::class,
    ],
    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
        ],
    ],
];
