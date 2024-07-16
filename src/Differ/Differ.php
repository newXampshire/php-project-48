<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;

use function Differ\Formatters\Json\format as jsonFormat;
use function Differ\Formatters\Plain\format as plainFormat;
use function Differ\Formatters\Stylish\format as stylishFormat;
use function Differ\Handlers\parse;

const FORMAT_STYLISH = 'stylish';
const FORMAT_PLAIN = 'plain';
const FORMAT_JSON = 'json';

function genDiff(string $oldFileName, string $newFileName, string $format = FORMAT_STYLISH): string
{
    $result = buildDifference(parse($oldFileName), parse($newFileName));

    /** @phpstan-ignore-next-line */
    return match ($format) {
        FORMAT_STYLISH => stylishFormat($result),
        FORMAT_PLAIN => plainFormat($result),
        FORMAT_JSON => jsonFormat($result)
    };
}
