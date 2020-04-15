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

$extensions = get_loaded_extensions(false);

// adding all extensions will result in a huge graph, so just pick 2 random ones
shuffle($extensions);
$extensions = array_slice($extensions, 0, 2);

foreach ($extensions as $extension) {
    $builder->createVertexExtension($extension);
}

$graphviz = new GraphViz();
// show UML diagram statements
echo $graphviz->createScript($graph);
// default format is PNG
echo $graphviz->createImageFile($graph) . ' file generated' . PHP_EOL;
