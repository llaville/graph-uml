# Multiple classes diagram

```php
<?php
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
    ]
);

$builder->createVertexClass(GraphUml\Generator\GraphVizGenerator::class);
$builder->createVertexClass(GraphUml\ClassDiagramBuilder::class);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
if (isset($argv[1])) {
    // target folder provided
    $cmdFormat = '%E -T%F %t -o ' . rtrim($argv[1], DIRECTORY_SEPARATOR) . '/multiple_classes.graphviz.%F';
} else {
    $cmdFormat = '';
}
$target = $generator->createImageFile($graph, $cmdFormat);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

Will output this [graph statements](./multiple_classes.html.gv).

And png file generated look like :

![Multiple Classes UML](./multiple_classes.graphviz.png)
