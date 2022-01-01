<!-- markdownlint-disable MD013 -->
# Single class diagram

In this example, we specify only one class, and this class with its direct dependencies will be rendered in graph.

```php
<?php
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
```

**NOTE**: Without custom `$options` provided with `ClassDiagramBuilder` class constructor,
all default values are used (see `ClassDiagramBuilderInterface::OPTIONS_DEFAULTS`)

Will output this [graph statements](./single_class.html.gv).

And image file generated look like :

![Single Class UML](./single_class.graphviz.svg)
