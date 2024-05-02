<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Formatters;

function format(array $data, int $prefixCnt = 4, int $offsetCnt = 2, int $level = 1): string
{
    $result = array_map(
        function ($key, $value) use ($prefixCnt, $offsetCnt, $level) {
            $prefix = str_repeat(' ', $level * $prefixCnt - $offsetCnt) . "$key: ";

            if (is_array($value)) {
                return $prefix . trim(format($value, $prefixCnt, $offsetCnt, ++$level), "\n");
            }

            $line = $prefix . (is_bool($value) || is_null($value) ? json_encode($value) : $value);
            return rtrim($line);
        },
        array_keys($data),
        array_values($data)
    );

    $prefix = str_repeat(' ', $prefixCnt * $level - $prefixCnt);

    return "{\n" . implode("\n", $result) . "\n$prefix}\n";
}
