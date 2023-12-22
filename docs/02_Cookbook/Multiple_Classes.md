<!-- markdownlint-disable MD013 -->
# Multiple classes diagram

In this example, we specify only two classes, and these classes with theirs direct dependencies will be rendered in graph.

```php
<?php
use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$options = [
    'label_format' => 'html',
    // @link https://graphviz.gitlab.io/docs/attrs/rankdir/
    'graph.rankdir' => 'LR',
    // @linkg https://graphviz.gitlab.io/docs/attrs/bgcolor/
    'graph.bgcolor' => 'transparent',
    // @link https://graphviz.gitlab.io/docs/attrs/fillcolor/
    'node.fillcolor' => '#FECECE',
    // @link https://graphviz.gitlab.io/docs/attrs/style/
    'node.style' => 'filled',
    // @link https://graphviz.gitlab.io/docs/attr-types/colorList/
    'cluster.Bartlett\\GraphUml.graph.bgcolor' => 'burlywood3',
    'cluster.Bartlett\\GraphUml\\Generator.graph.bgcolor' => 'burlywood3',
];

$datasource = [
    GraphVizGenerator::class,
    ClassDiagramBuilder::class,
];

$generator = new GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new ClassDiagramBuilder($generator, $graph, $options ?? []);

foreach ($datasource as $class) {
    $builder->createVertexClass($class, $options);
}

// show UML diagram statements
echo $generator->createScript($graph);

// default format is PNG, change it to SVG
$generator->setFormat('svg');

// generate binary image file
$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

Will output this [graph statements](../assets/images/multiple-classes.html.gv).

And image file generated look like :

![Multiple Classes UML](../assets/images/multiple-classes.graphviz.svg)
