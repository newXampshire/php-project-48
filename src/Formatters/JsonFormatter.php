<?php

declare(strict_types=1);

namespace Hexlet\Code\Differ\Formatters\Json;

function format(array $data): string
{
    return json_encode($data) . "\n";
}
