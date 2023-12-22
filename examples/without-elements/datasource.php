<?php declare(strict_types=1);

use Bartlett\GraphUml\ClassDiagramBuilder;

return function (): Generator
{
    $classes = [
        ClassDiagramBuilder::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
