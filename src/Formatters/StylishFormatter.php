<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Formatters\Stylish;

use const Hexlet\Code\Differ\Differ\ADDED;
use const Hexlet\Code\Differ\Differ\CHANGED;
use const Hexlet\Code\Differ\Differ\CHANGED_FROM;
use const Hexlet\Code\Differ\Differ\CHANGED_TO;
use const Hexlet\Code\Differ\Differ\CHILDREN;
use const Hexlet\Code\Differ\Differ\REMOVED;
use const Hexlet\Code\Differ\Differ\UNCHANGED;

const PREFIX_UNCHANGED = '  ';
const PREFIX_OLD_VALUE = '- ';
const PREFIX_NEW_VALUE = '+ ';

const OFFSET_CNT = 2;

function format(array $data, int $prefixCnt = 4, int $level = 1): string
{
    $result = [];

    $prefix = str_repeat(' ', $prefixCnt * $level - OFFSET_CNT);

    foreach ($data as $key => $item) {
        if (array_key_exists(CHILDREN, $item)) {
            $result[] = generateRow($prefix, $key, format($item[CHILDREN], $prefixCnt, $level + 1));
            continue;
        }

        switch (true) {
            case array_key_exists(REMOVED, $item):
                $value = convert($item[REMOVED], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $value, PREFIX_OLD_VALUE);
                break;
            case array_key_exists(ADDED, $item):
                $value = convert($item[ADDED], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $value, PREFIX_NEW_VALUE);
                break;
            case array_key_exists(CHANGED, $item):
                $old = convert($item[CHANGED][CHANGED_FROM], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $old, PREFIX_OLD_VALUE);

                $new = convert($item[CHANGED][CHANGED_TO], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $new, PREFIX_NEW_VALUE);
                break;
            default:
                $value = convert($item[UNCHANGED], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $value);
        }
    }

    $rootPrefix = str_repeat(' ', $prefixCnt * $level - $prefixCnt);

    return "{\n" . implode("\n", $result) . "\n$rootPrefix}\n";
}

function generateRow(string $prefix, string $key, mixed $value, string $prefixWithModifier = PREFIX_UNCHANGED): string
{
    $row = $prefix . $prefixWithModifier . "$key: " . $value;
    return rtrim($row);
}

function convert(mixed $data, int $prefixCnt, int $level): mixed
{
    return is_array($data) ?
        convertArray($data, $prefixCnt, $level + 1) :
        convertScalar($data);
}

function convertScalar(mixed $data): mixed
{
    return (is_bool($data) || is_null($data) ? json_encode($data) : $data);
}

function convertArray(array $data, int $prefixCnt, int $level): string
{
    $result = [];

    $prefix = str_repeat(' ', $prefixCnt * $level - OFFSET_CNT) . PREFIX_UNCHANGED;
    $rootPrefix = str_repeat(' ', $prefixCnt * $level - $prefixCnt);

    foreach ($data as $key => $item) {
        $prefixWithKey = $prefix . "$key: ";
        $value = is_array($item) ?
            convertArray($item, $prefixCnt, $level + 1) :
            (is_bool($item) || is_null($item) ? json_encode($item) : $item);

        $result[] = $prefixWithKey . $value;
    }

    return "{\n" . implode("\n", $result) . "\n$rootPrefix}";
}
