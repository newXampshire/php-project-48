<?php

declare(strict_types=1);

namespace Differ\Differ;

const CHILDREN = 'children';

const ADDED = 'added';
const REMOVED = 'removed';
const UNCHANGED = 'unchanged';

const CHANGED = 'changed';
const CHANGED_FROM = 'from';
const CHANGED_TO = 'to';

function buildDifference(array $old, array $new): array
{
    $result = [];

    /** @phpstan-ignore-next-line */
    foreach (array_diff_key($old, $new) as $key => $value) {
        $result[$key] = [REMOVED => $value]; /** @phpstan-ignore-line */
    }

    /** @phpstan-ignore-next-line */
    foreach (array_diff_key($new, $old) as $key => $value) {
        $result[$key] = [ADDED => $value]; /** @phpstan-ignore-line */
    }

    /** @phpstan-ignore-next-line */
    foreach (array_intersect_key($old, $new) as $key => $oldItem) {
        $newItem = $new[$key]; /** @phpstan-ignore-line */

        if (is_array($oldItem) && is_array($newItem)) {
            $result[$key][CHILDREN] = buildDifference($oldItem, $newItem); /** @phpstan-ignore-line */
            continue;
        }

        if ($oldItem === $newItem) {
            $result[$key] = [UNCHANGED => $oldItem]; /** @phpstan-ignore-line */
            continue;
        }

        /** @phpstan-ignore-next-line */
        $result[$key] = [CHANGED => [
            CHANGED_FROM => $oldItem,
            CHANGED_TO => $newItem]
        ];
    }

    ksort($result); /** @phpstan-ignore-line */

    return $result;
}
