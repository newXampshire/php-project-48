<?php

declare(strict_types=1);

namespace Tests\Unit;

use Exception;
use phpmock\Mock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Differ\Handlers\parse;
use function Hexlet\Code\Differ\Handlers\prepareJson;
use function Hexlet\Code\Differ\Handlers\prepareYaml;

class FileHandlerTest extends TestCase
{
    function testParseSuccess(): void
    {
        self::assertEquals(
            json_decode(file_get_contents(__DIR__ . '/../../fixtures/json/file1.json'), true),
            parse('file1.json')
        );

        self::assertEquals(
            Yaml::parseFile(__DIR__ . '/../../fixtures/yaml/file1.yaml'),
            parse('file1.yaml')
        );
    }

    function testParseWithNotSupportedFormat(): void
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Not supported file format');

        parse('file1.txt');
    }

    function testParseWhenFileGetContentsReturnFalse(): void
    {
        $fileName = 'some.json';

        $mock = new Mock('Hexlet\Code\Differ\Handlers', 'file_get_contents', fn() => false);
        $mock->enable();

        self::expectException(Exception::class);
        self::expectExceptionMessage('Could not read file');

        parse($fileName);

        $mock->disable();
    }

    function testParseWithNotExistentFile(): void
    {
        $fileName = 'some.json';

        self::expectException(Exception::class);
        self::expectExceptionMessage('Could not read file');

        parse($fileName);
    }

    function testParseJsonSuccess(): void
    {
        $file = file_get_contents(__DIR__ . '/../../fixtures/json/file1.json');

        self::assertEquals(
            json_decode($file, true),
            prepareJson($file)
        );
    }

    function testParseYamlSuccess(): void
    {
        $file = file_get_contents(__DIR__ . '/../../fixtures/yaml/file1.yaml');

        self::assertEquals(
            Yaml::parse($file),
            prepareYaml($file)
        );
    }
}
