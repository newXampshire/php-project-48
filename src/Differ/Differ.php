<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Differ;

const PREFIX_UNCHANGED = '  ';
const PREFIX_OLD_VALUE = '- ';
const PREFIX_NEW_VALUE = '+ ';

function diff(array $old, array $new): array
{
    $result = [];
    foreach ($old as $key => $oldItem) {
        if (!array_key_exists($key, $new)) {
            $result[PREFIX_OLD_VALUE . $key] = fillPrefix($oldItem);
            continue;
        }

        $newItem = $new[$key];

        if (is_array($oldItem) && is_array($newItem)) {
            $result[PREFIX_UNCHANGED . $key] = diff($oldItem, $newItem);
            continue;
        }

        $newItem = fillPrefix($newItem);
        $oldItem = fillPrefix($oldItem);

        if ($oldItem === $newItem) {
            $result[PREFIX_UNCHANGED . $key] = $oldItem;
            continue;
        }

        $result[PREFIX_OLD_VALUE . $key] = $oldItem;
        $result[PREFIX_NEW_VALUE . $key] = $newItem;
    }

    foreach ($new as $key => $newItem) {
        if (array_key_exists($key, $old)) {
            continue;
        }

        $result[PREFIX_NEW_VALUE . $key] = fillPrefix($newItem);
    }

    return sortResult($result);
}

function sortResult(array $data): array
{
    uksort(
        $data,
        fn($k1, $k2) => substr($k1, 2) <=> substr($k2, 2)
    );

    return $data;
}

function fillPrefix(mixed $data, $prefix = PREFIX_UNCHANGED): mixed
{
    if (!is_array($data)) {
        return $data;
    }

    $result = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = fillPrefix($value, $prefix);
        }

        $result[$prefix . $key] = $value;
    }

    return $result;
}
