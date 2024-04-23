<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Handlers;

use Exception;
use Symfony\Component\Yaml\Yaml;
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

    try {
        if (($file = file_get_contents(__DIR__ . "/../../files/$extension/" . $fileName)) === false) {
            throw new Exception();
        }
    } catch (Throwable) {
        throw new Exception('Could not read file');
    }

    return match ($extension) {
        FORMAT_JSON => prepareJson($file),
        FORMAT_YAML => prepareYaml($file)
    };
}

function prepareJson(string $file): array
{
    return json_decode($file, true);
}

function prepareYaml(string $file): array
{
    return  Yaml::parse($file);
}
