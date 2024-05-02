<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Differ\fillPrefix;
use function Hexlet\Code\Differ\Differ\diff;
use function Hexlet\Code\Differ\Differ\sortResult;

use const Hexlet\Code\Differ\Differ\PREFIX_NEW_VALUE;
use const Hexlet\Code\Differ\Differ\PREFIX_OLD_VALUE;
use const Hexlet\Code\Differ\Differ\PREFIX_UNCHANGED;

class DifferTest extends TestCase
{
    public static function differDataProvider(): array
    {
        return [
            [
                ['name' => 'some'],
                ['name' => 'some', 'new' => 'line'],
                [
                    '  name' => 'some',
                    '+ new' => 'line',
                ],
            ],
            [
                ['name' => 'some'],
                ['name' => 'new'],
                [
                    '- name' => 'some',
                    '+ name' => 'new',
                ],
            ],
            [
                ['name' => 'some'],
                ['new' => 'line'],
                [
                    '- name' => 'some',
                    '+ new' => 'line',
                ],
            ],
            [
                ['name' => 'some', 'old' => 'line'],
                ['name' => 'some', 'new' => 'line'],
                [
                    '  name' => 'some',
                    '+ new' => 'line',
                    '- old' => 'line',
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
                    '  root1' => [
                        '  name' => 'some',
                        '+ new' => 'line',
                        '- old' => 'line',
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
                    '  root1' => [
                        '  name' => 'some',
                    ],
                    '+ root2' => [
                        '  name' => 'some',
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
                    '  root1' => [
                        '  name' => 'some',
                    ],
                    '- root2' => [
                        '  name' => 'some',
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
                    '  root1' => [
                        '  sub1' => [
                            '  sub2' => [
                                '- some' => 'name',
                                '+ new' => 'name'
                            ],
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
            diff($old, $new)
        );
    }

    public function testSortDifference(): void
    {
        $data = [
            '  b' => 'some',
            '  e' => 'some',
            '  a' => 'some',
            '  g' => 'some',
            '- f' => 'old',
            '+ f' => 'new',
            '- c' => 'old',
            '+ c' => 'new',
            '+ d' => 'some',
        ];

        self::assertEquals([
            '  a' => 'some',
            '  b' => 'some',
            '- c' => 'old',
            '+ c' => 'new',
            '+ d' => 'some',
            '  e' => 'some',
            '- f' => 'old',
            '+ f' => 'new',
            '  g' => 'some',
        ],
            sortResult($data)
        );
    }

    public function testSortDifferenceWithMultipleArray(): void
    {
        $data = [
            '  b' => [
                '  e' => [
                    '- c' => 2,
                    '  a' => 1,
                    '+ c' => 3
                ],
                '  a' => [
                    '  z' => 1,
                    '  a' => 3,
                ],
                '  g' => [
                    '+ b' => 2,
                    '- a' => 10,
                    '  q' => 3
                ],
            ],
            '  a' => 'some'
        ];

        self::assertEquals([
            '  a' => 'some',
            '  b' => [
                '  a' => [
                    '  a' => 3,
                    '  z' => 1,
                ],
                '  e' => [
                    '  a' => 1,
                    '- c' => 2,
                    '+ c' => 3
                ],
                '  g' => [
                    '- a' => 10,
                    '+ b' => 2,
                    '  q' => 3
                ],
            ],
        ],
            sortResult($data)
        );
    }

    public static function prefixDataProvider(): array
    {
        return [
            PREFIX_UNCHANGED,
            PREFIX_OLD_VALUE,
            PREFIX_NEW_VALUE
        ];
    }

    #[DataProvider('prefixDataProvider')]
    public function fillPrefixTest(string $prefix): void
    {
        $data = [
            '..one' => [
                '..two' => 2,
                '..three' => 3,
                '..four' => [
                    '..five' => 5
                ],
            ],
            '..six' => 6
        ];

        $testData = json_decode(str_replace('..', '', json_encode($data)), true);
        $expectedData = json_decode(str_replace('..', $prefix, json_encode($data)), true);

        self::assertEquals($expectedData, fillPrefix($testData, $prefix));
    }
}
