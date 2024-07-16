<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

use const Differ\Differ\ADDED;
use const Differ\Differ\CHANGED;
use const Differ\Differ\CHANGED_FROM;
use const Differ\Differ\CHANGED_TO;
use const Differ\Differ\CHILDREN;
use const Differ\Differ\REMOVED;
use const Differ\Differ\UNCHANGED;

const PREFIX_UNCHANGED = '  ';
const PREFIX_OLD_VALUE = '- ';
const PREFIX_NEW_VALUE = '+ ';

const OFFSET_CNT = 2;

function format(array $data, int $prefixCnt = 4, int $level = 1): string
{
    $result = [];

    $prefix = str_repeat(' ', $prefixCnt * $level - OFFSET_CNT);

    /** @phpstan-ignore-next-line */
    foreach ($data as $key => $item) {
        if (array_key_exists(CHILDREN, $item)) {
            /** @phpstan-ignore-next-line */
            $result[] = generateRow($prefix, $key, rtrim(format($item[CHILDREN], $prefixCnt, $level + 1)));
            continue;
        }

        switch (true) {
            case array_key_exists(REMOVED, $item):
                $value = convert($item[REMOVED], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $value, PREFIX_OLD_VALUE); /** @phpstan-ignore-line */
                break;
            case array_key_exists(ADDED, $item):
                $value = convert($item[ADDED], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $value, PREFIX_NEW_VALUE); /** @phpstan-ignore-line */
                break;
            case array_key_exists(CHANGED, $item):
                $old = convert($item[CHANGED][CHANGED_FROM], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $old, PREFIX_OLD_VALUE); /** @phpstan-ignore-line */

                $new = convert($item[CHANGED][CHANGED_TO], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $new, PREFIX_NEW_VALUE); /** @phpstan-ignore-line */
                break;
            default:
                $value = convert($item[UNCHANGED], $prefixCnt, $level);
                $result[] = generateRow($prefix, $key, $value); /** @phpstan-ignore-line */
        }
    }

    $rootPrefix = str_repeat(' ', $prefixCnt * $level - $prefixCnt);

    return "{\n" . implode("\n", $result) . "\n$rootPrefix}\n";
}

function generateRow(string $prefix, string $key, mixed $value, string $prefixWithModifier = PREFIX_UNCHANGED): string
{
    return $prefix . $prefixWithModifier . "$key: " . $value;
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

    /** @phpstan-ignore-next-line */
    foreach ($data as $key => $item) {
        $prefixWithKey = $prefix . "$key: ";
        $value = is_array($item) ?
            convertArray($item, $prefixCnt, $level + 1) :
            (is_bool($item) || is_null($item) ? json_encode($item) : $item);

        $result[] = $prefixWithKey . $value; /** @phpstan-ignore-line */
    }

    return "{\n" . implode("\n", $result) . "\n$rootPrefix}";
}
