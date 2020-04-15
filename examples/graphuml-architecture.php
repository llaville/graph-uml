<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Bartlett\GraphUml;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$graph = new Graph();
$builder = new GraphUml\ClassDiagramBuilder(
    $graph,
    [
        'label-format' => 'html',
    ]
);

$builder->createVertexClass(GraphUml\Formatter\AbstractFormatterFactory::class);
$builder->createVertexClass(GraphUml\Formatter\FormatterFactory::class);
$builder->createVertexClass(GraphUml\Formatter\FormatterFactoryInterface::class);
$builder->createVertexClass(GraphUml\Formatter\HtmlFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\RecordFormatter::class);
$builder->createVertexClass(GraphUml\FormatterInterface::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilder::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilderInterface::class);

$graphviz = new GraphViz();
// show UML diagram statements
echo $graphviz->createScript($graph);
// default format is PNG
echo $graphviz->createImageFile($graph) . ' file generated' . PHP_EOL;
