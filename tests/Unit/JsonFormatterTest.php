<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Formatters\format;

class JsonFormatterTest extends TestCase
{
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

        $data = [
            '- follow' => false,
            '  host' => 'hexlet.io',
            '- proxy' => '123.234.53.22',
            '- timeout' => 50,
            '+ timeout' => 20,
            '+ verbose' => true,
        ];

        self::assertEquals($expectedData, format($data));
    }
}
