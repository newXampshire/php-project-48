<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

use const Differ\Differ\ADDED;
use const Differ\Differ\CHANGED;
use const Differ\Differ\CHANGED_FROM;
use const Differ\Differ\CHANGED_TO;
use const Differ\Differ\CHILDREN;
use const Differ\Differ\REMOVED;
use const Differ\Differ\UNCHANGED;

const FORMAT_ADDED = "Property '%s' was added with value: %s";
const FORMAT_UPDATED = "Property '%s' was updated. From %s to %s";
const FORMAT_REMOVED = "Property '%s' was removed";

function format(array $data, string $prefixKey = ''): string
{
    $result = [];

    /** @phpstan-ignore-next-line */
    foreach ($data as $key => $item) {
        $key = $prefixKey . $key; /** @phpstan-ignore-line */

        if (array_key_exists(CHILDREN, $item)) {
            $result[] = rtrim(format($item[CHILDREN], "$key.")); /** @phpstan-ignore-line */
            continue;
        }

        if (array_key_exists(UNCHANGED, $item)) {
            continue;
        }

        /** @phpstan-ignore-next-line */
        $result[] = match (true) {
            array_key_exists(REMOVED, $item) => sprintf(FORMAT_REMOVED, $key),
            array_key_exists(ADDED, $item) => sprintf(FORMAT_ADDED, $key, convert($item[ADDED])),
            default => sprintf(
                FORMAT_UPDATED,
                $key,
                convert($item[CHANGED][CHANGED_FROM]),
                convert($item[CHANGED][CHANGED_TO])
            )
        };
    }

    return implode("\n", $result) . "\n";
}

function convert(mixed $data): mixed
{
    return match (true) {
        is_string($data) => "'$data'",
        is_array($data) => '[complex value]',
        is_bool($data) || is_null($data) => json_encode($data),
        default => $data
    };
}
