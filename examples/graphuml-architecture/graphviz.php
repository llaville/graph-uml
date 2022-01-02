<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

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
        'show_protected' => false,
    ]
);

$builder->createVertexClass(GraphUml\Formatter\HtmlFormatter::class);
$builder->createVertexClass(GraphUml\Formatter\RecordFormatter::class);
$builder->createVertexClass(GraphUml\Generator\GraphVizGenerator::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilder::class);

// For large graph, orientation is recommended
// https://graphviz.gitlab.io/docs/attrs/rankdir/
$graph->setAttribute($generator->getPrefix() . 'graph.rankdir', 'LR');
// https://graphviz.gitlab.io/docs/attrs/bgcolor/
$graph->setAttribute($generator->getPrefix() . 'graph.bgcolor', 'transparent');
// https://graphviz.gitlab.io/docs/attrs/fillcolor/
$graph->setAttribute($generator->getPrefix() . 'node.fillcolor', 'lightgrey');
// https://graphviz.gitlab.io/docs/attrs/style/
$graph->setAttribute($generator->getPrefix() . 'node.style', 'filled');

// To use this feature, use my fork of graphp/graphviz project (see composer.json)
$graph->setAttribute($generator->getPrefix() . 'cluster.2.graph.bgcolor', 'lightblue');
// Either numeric or class namespace is allowed (2 = Bartlett\GraphUml)
// https://graphviz.gitlab.io/docs/attr-types/colorList/
$graph->setAttribute($generator->getPrefix() . 'cluster.Bartlett\\GraphUml\\Generator.graph.style', 'filled');
$graph->setAttribute($generator->getPrefix() . 'cluster.Bartlett\\GraphUml\\Generator.graph.fillcolor', 'yellow:blue');
$graph->setAttribute($generator->getPrefix() . 'cluster.Bartlett\\GraphUml\\Generator.graph.gradientangle', 45);
$graph->setAttribute($generator->getPrefix() . 'cluster.Bartlett\\GraphUml\\Formatter.graph.style', 'radial');
$graph->setAttribute($generator->getPrefix() . 'cluster.Bartlett\\GraphUml\\Formatter.graph.fillcolor', 'yellow:blue');
$graph->setAttribute($generator->getPrefix() . 'cluster.Bartlett\\GraphUml\\Formatter.graph.gradientangle', 180);

// show UML diagram statements
echo $generator->createScript($graph);

// default format is PNG, change it to SVG
$generator->setFormat('svg');

// generate binary image file
$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
