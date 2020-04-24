<?php

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Bartlett\GraphUml;

use Graphp\Graph\Graph;

$generator = new GraphUml\Generator\GraphVizGenerator();
$graph = new Graph();
$builder = new GraphUml\ClassDiagramBuilder(
    $generator,
    $graph,
    [
        'label-format' => 'html',
    ]
);

$builder->createVertexClass(GraphUml\Formatter\AbstractFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\HtmlFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\RecordFormatter::class);
$builder->createVertexClass(GraphUml\FormatterInterface::class);
$builder->createVertexClass(GraphUml\Generator\AbstractGeneratorTrait::class);
$builder->createVertexClass(GraphUml\Generator\GeneratorInterface::class);
$builder->createVertexClass(GraphUml\Generator\GraphVizGenerator::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilder::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilderInterface::class);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
echo $generator->createImageFile($graph) . ' file generated' . PHP_EOL;
