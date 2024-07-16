<?php

declare(strict_types=1);

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\Stylish\format;

class StylishFormatterTest extends TestCase
{
    use Helper;

    public function testFormatJsonSuccess(): void
    {
        $expectedData = <<<DATA
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}

DATA;

        self::assertEquals($expectedData, format(self::$simpleDiff));
    }

    public function testFormatJsonSuccessWithMultipleArray(): void
    {
        $expectedData = <<<DATA
{
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}

DATA;

        self::assertEquals($expectedData, format(self::$multipleDiff));
    }
}
