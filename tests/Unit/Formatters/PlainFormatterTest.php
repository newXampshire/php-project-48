<?php

declare(strict_types=1);

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Formatters\Plain\format;

class PlainFormatterTest extends TestCase
{
    use Helper;

    public function testFormatJsonSuccess(): void
    {
        $expectedData = <<<DATA
Property 'follow' was removed
Property 'proxy' was removed
Property 'timeout' was updated. From 50 to 20
Property 'verbose' was added with value: true

DATA;

        self::assertEquals($expectedData, format(self::$simpleDiff));
    }

    public function testFormatJsonSuccessWithMultipleArray(): void
    {
        $expectedData = <<<DATA
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]

DATA;

        self::assertEquals($expectedData, format(self::$multipleDiff));
    }
}
