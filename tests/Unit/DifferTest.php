<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Differ\generateDifference;
use function Hexlet\Code\Differ\Differ\sortResult;

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
        ];
    }

    #[DataProvider('differDataProvider')]
    public function testGenerateDifference(array $old, array $new, array $expectedResult): void
    {
        self::assertEquals(
            $expectedResult,
            generateDifference($old, $new)
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

        $this->assertEquals([
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
}
