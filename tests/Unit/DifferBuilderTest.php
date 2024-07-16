<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\buildDifference;

use const Differ\Differ\ADDED;
use const Differ\Differ\CHANGED;
use const Differ\Differ\CHANGED_FROM;
use const Differ\Differ\CHANGED_TO;
use const Differ\Differ\CHILDREN;
use const Differ\Differ\REMOVED;
use const Differ\Differ\UNCHANGED;

class DifferBuilderTest extends TestCase
{
    public static function differDataProvider(): array
    {
        return [
            [
                ['name' => 'some'],
                ['name' => 'some', 'new' => 'line'],
                [
                    'name' => [UNCHANGED => 'some'],
                    'new' => [ADDED => 'line'],
                ],
            ],

            [
                ['name' => 'some'],
                ['name' => 'new'],
                [
                    'name' => [CHANGED => [CHANGED_FROM => 'some', CHANGED_TO => 'new']],
                ],
            ],

            [
                ['name' => 'some'],
                ['new' => 'line'],
                [
                    'name' => [REMOVED => 'some'],
                    'new' => [ADDED => 'line'],
                ],
            ],

            [
                ['name' => 'some', 'old' => 'line'],
                ['name' => 'some', 'new' => 'line'],
                [
                    'name' => [UNCHANGED => 'some'],
                    'new' => [ADDED => 'line'],
                    'old' => [REMOVED => 'line'],
                ],
            ],

            [
                [
                    'root1' => [
                        'name' => 'some',
                        'old' => 'line'
                    ],
                ],
                [
                    'root1' => [
                        'name' => 'some',
                        'new' => 'line'
                    ],
                ],
                [
                    'root1' => [
                        CHILDREN => [
                            'name' => [UNCHANGED => 'some'],
                            'new' => [ADDED => 'line'],
                            'old' => [REMOVED => 'line'],
                        ]
                    ],
                ],
            ],

            [
                [
                    'root1' => [
                        'name' => 'some',
                    ],
                ],
                [
                    'root1' => [
                        'name' => 'some',
                    ],
                    'root2' => [
                        'name' => 'some',
                    ],
                ],
                [
                    'root1' => [
                        CHILDREN => [
                            'name' => [UNCHANGED => 'some'],
                        ],
                    ],
                    'root2' => [
                        ADDED => [
                            'name' => 'some',
                        ],
                    ],
                ],
            ],

            [
                [
                    'root1' => [
                        'name' => 'some',
                    ],
                    'root2' => [
                        'name' => 'some',
                    ],
                ],
                [
                    'root1' => [
                        'name' => 'some',
                    ],
                ],
                [
                    'root1' => [
                        CHILDREN => [
                            'name' => [UNCHANGED => 'some'],
                        ],
                    ],
                    'root2' => [
                        REMOVED => [
                            'name' => 'some',
                        ],
                    ],
                ],
            ],

            [
                [
                    'root1' => [
                        'sub1' => [
                            'sub2' => [
                                'some' => 'name'
                            ],
                        ],
                    ],
                ],
                [
                    'root1' => [
                        'sub1' => [
                            'sub2' => [
                                'new' => 'name'
                            ],
                        ],
                    ],
                ],
                [
                    'root1' => [
                        CHILDREN => [
                            'sub1' => [CHILDREN => [
                                'sub2' => [CHILDREN => [
                                    'some' => [REMOVED => 'name'],
                                    'new' => [ADDED => 'name'],
                                ]]
                            ]],
                        ],
                    ],
                ],
            ],

        ];
    }

    #[DataProvider('differDataProvider')]
    public function testGenerateDifference(array $old, array $new, array $expectedResult): void
    {
        self::assertEquals(
            $expectedResult,
            buildDifference($old, $new)
        );
    }
}
