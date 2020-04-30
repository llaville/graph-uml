<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Bartlett\GraphUml;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$generator = new GraphUml\Generator\GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new GraphUml\ClassDiagramBuilder(
    $generator,
    $graph,
    [
        'label-format' => 'html',
        'show-constants' => false,
        'show-private' => false,
        'show-protected' => false
    ]
);

$builder->createVertexClass(GraphUml\Formatter\AbstractFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\HtmlFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\RecordFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\FormatterInterface::class);
$builder->createVertexClass(GraphUml\Generator\AbstractGenerator::class);
$builder->createVertexClass(GraphUml\Generator\GeneratorInterface::class);
$builder->createVertexClass(GraphUml\Generator\GraphVizGenerator::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilder::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilderInterface::class);

// For large graph, orientation is recommended
// https://graphviz.gitlab.io/_pages/doc/info/attrs.html#d:rankdir
$graph->setAttribute($generator->getName() . '.graph.rankdir', 'LR');

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
echo $generator->createImageFile($graph) . ' file generated' . PHP_EOL;
