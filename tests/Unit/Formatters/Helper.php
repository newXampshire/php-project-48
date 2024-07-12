<?php

declare(strict_types=1);

namespace Tests\Unit\Formatters;

trait Helper
{
    protected static array $simpleDiff = [
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

    protected static array $multipleDiff = [
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
}
