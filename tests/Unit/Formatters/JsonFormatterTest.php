<?php

declare(strict_types=1);

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Formatters\Json\format;

class JsonFormatterTest extends TestCase
{
    use Helper;

    public function testFormatJsonSuccess(): void
    {
        self::assertEquals(
            json_encode(self::$simpleDiff) . "\n",
            format(self::$simpleDiff)
        );
    }

    public function testFormatJsonSuccessWithMultipleArray(): void
    {
        self::assertEquals(
            json_encode(self::$multipleDiff) . "\n",
            format(self::$multipleDiff)
        );
    }
}
