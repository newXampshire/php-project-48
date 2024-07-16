<?php

declare(strict_types=1);

namespace Differ\Formatters\Json;

function format(array $data): string
{
    return json_encode($data) . "\n";
}
