<?php declare(strict_types=1);

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

return function (): Generator
{
    $classes = [
        ClassDiagramBuilder::class,
        GraphVizGenerator::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
