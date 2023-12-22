<?php declare(strict_types=1);

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Formatter\HtmlFormatter;
use Bartlett\GraphUml\Formatter\RecordFormatter;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

return function (): Generator
{
    $classes = [
        HtmlFormatter::class,
        RecordFormatter::class,
        GraphVizGenerator::class,
        ClassDiagramBuilder::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
