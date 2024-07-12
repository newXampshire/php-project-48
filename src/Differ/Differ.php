<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Differ;

const CHILDREN = 'children';

const ADDED = 'added';
const REMOVED = 'removed';
const UNCHANGED = 'unchanged';

const CHANGED = 'changed';
const CHANGED_FROM = 'from';
const CHANGED_TO = 'to';

function diff(array $old, array $new): array
{
    $result = [];

    foreach (array_diff_key($old, $new) as $key => $value) {
        $result[$key] = [REMOVED => $value];
    }

    foreach (array_diff_key($new, $old) as $key => $value) {
        $result[$key] = [ADDED => $value];
    }

    foreach (array_intersect_key($old, $new) as $key => $oldItem) {
        $newItem = $new[$key];

        if (is_array($oldItem) && is_array($newItem)) {
            $result[$key][CHILDREN] = diff($oldItem, $newItem);
            continue;
        }

        if ($oldItem === $newItem) {
            $result[$key] = [UNCHANGED => $oldItem];
            continue;
        }

        $result[$key] = [CHANGED => [
            CHANGED_FROM => $oldItem,
            CHANGED_TO => $newItem]
        ];
    }

    ksort($result);

    return $result;
}
