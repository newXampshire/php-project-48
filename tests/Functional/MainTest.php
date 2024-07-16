<?php

declare(strict_types=1);

namespace Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

use const Differ\Differ\FORMAT_JSON;
use const Differ\Differ\FORMAT_PLAIN;
use const Differ\Differ\FORMAT_STYLISH;

class MainTest extends TestCase
{
    public static function filesDataProvider(): array
    {
        return [
            [
                __DIR__ . '/../fixtures/json/file1.json',
                __DIR__ . '/../fixtures/json/file2.json',
                'simple.txt'
            ],
            [
                __DIR__ . '/../fixtures/json/fileBig1.json',
                __DIR__ . '/../fixtures/json/fileBig2.json', 'multiple.txt'],
            [
                __DIR__ . '/../fixtures/yaml/file1.yaml',
                __DIR__ . '/../fixtures/yaml/file2.yaml',
                'simple.txt'
            ],
            [
                __DIR__ . '/../fixtures/yaml/fileBig1.yaml',
                __DIR__ . '/../fixtures/yaml/fileBig2.yaml',
                'multiple.txt'
            ],
            [
                __DIR__ . '/../fixtures/json/file1.json',
                __DIR__ . '/../fixtures/yaml/file2.yaml',
                'simple.txt'
            ],
            [
                __DIR__ . '/../fixtures/yaml/fileBig1.yaml',
                __DIR__ . '/../fixtures/json/fileBig2.json',
                'multiple.txt'
            ],
        ];
    }

    #[DataProvider('filesDataProvider')]
    public function testGenDiffStylish(string $file1, string $file2, string $expected): void
    {
        self::assertEquals(
            file_get_contents(__DIR__ . '/../expected/stylish/' . $expected),
            genDiff($file1, $file2, FORMAT_STYLISH)
        );
    }

    #[DataProvider('filesDataProvider')]
    public function testGenDiffPlain(string $file1, string $file2, string $expected): void
    {
        self::assertEquals(
            file_get_contents(__DIR__ . '/../expected/plain/' . $expected),
            genDiff($file1, $file2, FORMAT_PLAIN)
        );
    }

    #[DataProvider('filesDataProvider')]
    public function testGenDiffJson(string $file1, string $file2, string $expected): void
    {
        self::assertEquals(
            file_get_contents(__DIR__ . '/../expected/json/' . $expected),
            genDiff($file1, $file2, FORMAT_JSON)
        );
    }
}
