<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Handlers;

use Exception;
use Throwable;

use function Hexlet\Code\Differ\Differ\generateDifference;

const FORMAT_JSON = 'json';
const FORMAT_YAML = 'yaml';

const ALLOWED_FORMATS = [FORMAT_JSON, FORMAT_YAML];

function genDiff(string $oldFileName, string $newFileName): array
{
    return generateDifference(parse($oldFileName), parse($newFileName));
}

function parse(string $fileName): array
{
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    if (!in_array($extension, ALLOWED_FORMATS)) {
        throw new Exception('Not supported file format');
    }

    return match ($extension) {
        FORMAT_JSON => prepareJson($fileName),
        FORMAT_YAML => prepareYaml($fileName)
    };
}

function prepareJson(string $fileName): array
{
    try {
        if (($file = file_get_contents(__DIR__ . '/../../files/json/' . $fileName)) === false) {
            throw new Exception();
        }
    } catch (Throwable) {
        throw new Exception('Could not read file');
    }

    return json_decode($file, true);
}

function prepareYaml(string $fileName): array
{
    return [];
}
