<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Differ;

const PREFIX_UNCHANGED = '  ';
const PREFIX_OLD_VALUE = '- ';
const PREFIX_NEW_VALUE = '+ ';

function generateDifference(array $old, array $new): array
{
    $result = [];
    foreach ($old as $key => $oldItem) {
        $newItem = $new[$key] ?? null;
        if ($oldItem === $newItem) {
            $result[PREFIX_UNCHANGED . $key] = $oldItem;
            continue;
        }

        $result[PREFIX_OLD_VALUE . $key] = $oldItem;
        if ($newItem !== null) {
            $result[PREFIX_NEW_VALUE . $key] = $newItem;
        }
    }

    foreach (array_diff($new, $old) as $key => $newItem) {
        $result[PREFIX_NEW_VALUE . $key] = $newItem;
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
