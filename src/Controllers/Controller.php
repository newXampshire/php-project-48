<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Controllers;

use function Hexlet\Code\Differ\Differ\diff;
use function Hexlet\Code\Differ\Formatters\Plain\format as plainFormat;
use function Hexlet\Code\Differ\Formatters\Stylish\format as stylishFormat;
use function Hexlet\Code\Differ\Handlers\parse;

const FORMAT_STYLISH = 'stylish';
const FORMAT_PLAIN = 'plain';

function generateDifference(string $oldFileName, string $newFileName, string $format): string
{
    $result = diff(parse($oldFileName), parse($newFileName));

    return match ($format) {
        FORMAT_STYLISH => stylishFormat($result),
        FORMAT_PLAIN => plainFormat($result)
    };
}
