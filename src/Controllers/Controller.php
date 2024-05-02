<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Controllers;

use function Hexlet\Code\Differ\Formatters\format as stylishFormat;
use function Hexlet\Code\Differ\Handlers\generateDifference;

const FORMAT_STYLISH = 'stylish';
const FORMAT_JSON = 'json';

function genDiff(string $oldFileName, string $newFileName, string $format): string
{
    $result = generateDifference($oldFileName, $newFileName);

    return match ($format) {
        FORMAT_STYLISH => stylishFormat($result),
    };
}
