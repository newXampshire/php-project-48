<?php

declare(strict_types=1);

namespace Differ\Handlers;

use Exception;
use Symfony\Component\Yaml\Yaml;
use Throwable;

const FORMAT_JSON = 'json';
const FORMAT_YML = 'yml';
const FORMAT_YAML = 'yaml';

const ALLOWED_FORMATS = [FORMAT_JSON, FORMAT_YML, FORMAT_YAML];

function parse(string $fileName): array
{
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    if (!in_array($extension, ALLOWED_FORMATS, true)) {
        throw new Exception('Not supported file format'); /** @phpstan-ignore-line */
    }

    try {
        if (($file = file_get_contents($fileName)) === false) {
            throw new Exception(); /** @phpstan-ignore-line */
        }
    } catch (Throwable) {
        throw new Exception('Could not read file'); /** @phpstan-ignore-line */
    }

    /** @phpstan-ignore-next-line */
    return match ($extension) {
        FORMAT_JSON => prepareJson($file),
        FORMAT_YML, FORMAT_YAML => prepareYaml($file),
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
