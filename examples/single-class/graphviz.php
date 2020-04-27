<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;

$generator = new GraphVizGenerator();
$graph = new Graph();
$builder = new ClassDiagramBuilder(
    $generator,
    $graph,
    [
        'label-format' => 'html',
    ]
);

$builder->createVertexClass(ClassDiagramBuilder::class);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
echo $generator->createImageFile($graph) . ' file generated' . PHP_EOL;
