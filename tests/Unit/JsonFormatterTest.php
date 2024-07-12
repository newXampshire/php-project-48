<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Formatters\Stylish\format;

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
            'follow' => [
                'removed' => false
            ],
            'host' => [
                'unchanged' => 'hexlet.io'
            ],
            'proxy' => [
                'removed' => '123.234.53.22'
            ],
            'timeout' => [
                'changed' => [
                    'from' => 50,
                    'to' => 20
                ]
            ],
            'verbose' => [
                'added' => true
            ]
        ];

        self::assertEquals($expectedData, format($data));
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

        $data = [
            'group1' => [
                'children' => [
                    'baz' => [
                        'changed' => [
                            'from' => 'bas',
                            'to' => 'bars'
                        ]
                    ],
                    'foo' => [
                        'unchanged' => 'bar'
                    ],
                    'nest' => [
                        'changed' => [
                            'from' => [
                                'key' => 'value'
                            ],
                            'to' => 'str'
                        ]
                    ]
                ]
            ],
            'group2' => [
                'removed' => [
                    'abc' => 12345,
                    'deep' => [
                        'id' => 45
                    ]
                ]
            ],
            'group3' => [
                'added' => [
                    'deep' => [
                        'id' => [
                            'number' => 45
                        ]
                    ],
                    'fee' => 100500
                ]
            ]
        ];

        self::assertEquals($expectedData, format($data));
    }
}
