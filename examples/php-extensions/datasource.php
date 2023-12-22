<?php declare(strict_types=1);

return function (): Generator
{
    $extensions = [
        'lzf',
        'yaml',
    ];
    foreach ($extensions as $extension) {
        yield $extension;
    }
};
