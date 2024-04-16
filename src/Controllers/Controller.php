<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Controllers;

use function Hexlet\Code\Differ\Formatters\format as jsonFormat;
use function Hexlet\Code\Differ\Handlers\genDiff as genDiffByFiles;

const FORMAT_STYLISH = 'stylish';
const FORMAT_JSON = 'json';

function genDiff(string $oldFileName, string $newFileName, string $format = FORMAT_JSON): string
{
    $result = genDiffByFiles($oldFileName, $newFileName);

    return match ($format) {
        FORMAT_JSON => jsonFormat($result),
    };
}
