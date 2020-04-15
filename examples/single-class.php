<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Bartlett\GraphUml\ClassDiagramBuilder;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$graph = new Graph();
$builder = new ClassDiagramBuilder(
    $graph,
    [
        'label-format' => 'html',
    ]
);

$builder->createVertexClass(ClassDiagramBuilder::class);

$graphviz = new GraphViz();
// show UML diagram statements
echo $graphviz->createScript($graph);
// default format is PNG
echo $graphviz->createImageFile($graph) . ' file generated' . PHP_EOL;
