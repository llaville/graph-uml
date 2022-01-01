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

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$generator = new GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new ClassDiagramBuilder(
    $generator,
    $graph,
    [
        'label_format' => 'html',
    ]
);

$builder->createVertexClass(ClassDiagramBuilder::class);

// For large graph, orientation is recommended
// https://graphviz.gitlab.io/docs/attrs/rankdir/
$graph->setAttribute($generator->getPrefix() . 'graph.rankdir', 'LR');
// https://graphviz.gitlab.io/docs/attrs/bgcolor/
$graph->setAttribute($generator->getPrefix() . 'graph.bgcolor', 'transparent');
// https://graphviz.gitlab.io/docs/attrs/fillcolor/
$graph->setAttribute($generator->getPrefix() . 'node.fillcolor', '#FEFECE');
// https://graphviz.gitlab.io/docs/attrs/style/
$graph->setAttribute($generator->getPrefix() . 'node.style', 'filled');

$clusters = [
    'Bartlett\\GraphUml',
];
foreach ($clusters as $cluster) {
    $attribute = sprintf('cluster.%s.graph.bgcolor', $cluster);
    $graph->setAttribute($generator->getPrefix() . $attribute, 'burlywood3');
}

// show UML diagram statements
echo $generator->createScript($graph);

// default format is PNG, change it to SVG
$generator->setFormat('svg');

// generate binary image file
$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
