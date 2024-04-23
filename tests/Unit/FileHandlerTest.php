<?php

declare(strict_types=1);

namespace Tests\Unit;

use Exception;
use phpmock\Mock;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Handlers\parse;
use function Hexlet\Code\Differ\Handlers\prepareJson;

class FileHandlerTest extends TestCase
{
    function testParseSuccess(): void
    {
        self::assertEquals(
            json_decode(file_get_contents(__DIR__ . '/../../files/json/file1.json'), true),
            parse('file1.json')
        );
    }

    function testParseWithNotSupportedFormat(): void
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Not supported file format');

        parse('file1.txt');
    }

    function testParseJsonSuccess(): void
    {
        self::assertEquals(
            json_decode(file_get_contents(__DIR__ . '/../../files/json/file1.json'), true),
            prepareJson('file1.json')
        );
    }

    function testParseJsonWhenFileGetContentsReturnFalse(): void
    {
        $fileName = 'some.json';

        $mock = new Mock('Hexlet\Code\Differ\Handlers', 'file_get_contents', fn() => false);
        $mock->enable();

        self::expectException(Exception::class);
        self::expectExceptionMessage('Could not read file');

        prepareJson($fileName);

        $mock->disable();
    }

    function testParseJsonWithNotExistentFile(): void
    {
        $fileName = 'some.json';

        self::expectException(Exception::class);
        self::expectExceptionMessage('Could not read file');

        prepareJson($fileName);
    }
}
