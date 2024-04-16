<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Formatters;

function format(array $data): string
{
    $result = array_map(
        fn($key, $value) => "  $key: " . (is_bool($value) ? json_encode($value) : $value) . "\n",
        array_keys($data),
        $data
    );

    return "{\n" . implode('', $result) . "}\n";
}
