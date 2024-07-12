<?php

declare(strict_types=1);

namespace Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Controllers\generateDifference;

use const Hexlet\Code\Differ\Controllers\FORMAT_PLAIN;
use const Hexlet\Code\Differ\Controllers\FORMAT_STYLISH;

class MainTest extends TestCase
{
    public static function filesDataProvider(): array
    {
        return [
            ['file1.json', 'file2.json', 'simple.txt'],
            ['fileBig1.json', 'fileBig2.json', 'multiple.txt'],
            ['file1.yaml', 'file2.yaml', 'simple.txt'],
            ['fileBig1.yaml', 'fileBig2.yaml', 'multiple.txt'],
            ['file1.json', 'file2.yaml', 'simple.txt'],
            ['fileBig1.yaml', 'fileBig2.json', 'multiple.txt'],
        ];
    }

    #[DataProvider('filesDataProvider')]
    public function testGenDiffStylish(string $file1, string $file2, string $expected): void
    {
        self::assertEquals(
            file_get_contents(__DIR__ . '/../expected/stylish/' . $expected),
            generateDifference($file1, $file2, FORMAT_STYLISH)
        );
    }

    #[DataProvider('filesDataProvider')]
    public function testGenDiffPlain(string $file1, string $file2, string $expected): void
    {
        self::assertEquals(
            file_get_contents(__DIR__ . '/../expected/plain/' . $expected),
            generateDifference($file1, $file2, FORMAT_PLAIN)
        );
    }
}
