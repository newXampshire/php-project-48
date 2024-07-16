<?php

declare(strict_types=1);

namespace Tests\Unit;

use Exception;
use phpmock\Mock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Differ\Handlers\parse;
use function Differ\Handlers\prepareJson;
use function Differ\Handlers\prepareYaml;

class FileHandlerTest extends TestCase
{
    public function testParseSuccess(): void
    {
        self::assertEquals(
            json_decode(file_get_contents(__DIR__ . '/../fixtures/json/file1.json'), true),
            parse(__DIR__ . '/../fixtures/json/file1.json')
        );

        self::assertEquals(
            Yaml::parseFile(__DIR__ . '/../fixtures/yaml/file1.yaml'),
            parse(__DIR__ . '/../fixtures/yaml/file1.yaml')
        );
    }

    public function testParseWithNotSupportedFormat(): void
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Not supported file format');

        parse('file1.txt');
    }

    public function testParseWhenFileGetContentsReturnFalse(): void
    {
        $fileName = 'some.json';

        $mock = new Mock('Differ\Handlers', 'file_get_contents', fn() => false);
        $mock->enable();

        self::expectException(Exception::class);
        self::expectExceptionMessage('Could not read file');

        parse($fileName);

        $mock->disable();
    }

    public function testParseWithNotExistentFile(): void
    {
        $fileName = 'some.json';

        self::expectException(Exception::class);
        self::expectExceptionMessage('Could not read file');

        parse($fileName);
    }

    public function testParseJsonSuccess(): void
    {
        $file = file_get_contents(__DIR__ . '/../fixtures/json/file1.json');

        self::assertEquals(
            json_decode($file, true),
            prepareJson($file)
        );
    }

    public function testParseYamlSuccess(): void
    {
        $file = file_get_contents(__DIR__ . '/../fixtures/yaml/file1.yaml');

        self::assertEquals(
            Yaml::parse($file),
            prepareYaml($file)
        );
    }
}
