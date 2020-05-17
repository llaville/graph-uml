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
        'label_format' => 'html',
        'show_constants' => false,
        'show_private' => false,
        'show_protected' => false
    ]
);

$builder->createVertexClass(GraphUml\Formatter\HtmlFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\RecordFormatter::class);
$builder->createVertexClass(GraphUml\Generator\GraphVizGenerator::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilder::class);

// For large graph, orientation is recommended
// https://graphviz.gitlab.io/_pages/doc/info/attrs.html#d:rankdir
$graph->setAttribute($generator->getName() . '.graph.rankdir', 'LR');
// https://graphviz.gitlab.io/_pages/doc/info/attrs.html#d:bgcolor
$graph->setAttribute($generator->getName() . '.graph.bgcolor', 'transparent');
// https://graphviz.gitlab.io/_pages/doc/info/attrs.html#d:fillcolor
$graph->setAttribute($generator->getName() . '.node.fillcolor', 'lightgrey');
// https://graphviz.gitlab.io/_pages/doc/info/attrs.html#d:style
$graph->setAttribute($generator->getName() . '.node.style', 'filled');

// To use this feature, use my fork of graphp/graphviz project (see composer.json)
$graph->setAttribute($generator->getName() . '.cluster.2.graph.bgcolor', 'lightblue');
// Either numeric or class namespace is allowed (2 = Bartlett\GraphUml)
// https://graphviz.gitlab.io/_pages/doc/info/attrs.html#k:colorList
$graph->setAttribute($generator->getName() . '.cluster.Bartlett\\GraphUml\\Generator.graph.style', 'filled');
$graph->setAttribute($generator->getName() . '.cluster.Bartlett\\GraphUml\\Generator.graph.fillcolor', 'yellow:blue');
$graph->setAttribute($generator->getName() . '.cluster.Bartlett\\GraphUml\\Generator.graph.gradientangle', 45);
$graph->setAttribute($generator->getName() . '.cluster.Bartlett\\GraphUml\\Formatter.graph.style', 'radial');
$graph->setAttribute($generator->getName() . '.cluster.Bartlett\\GraphUml\\Formatter.graph.fillcolor', 'yellow:blue');
$graph->setAttribute($generator->getName() . '.cluster.Bartlett\\GraphUml\\Formatter.graph.gradientangle', 180);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
echo $generator->createImageFile($graph) . ' file generated' . PHP_EOL;
