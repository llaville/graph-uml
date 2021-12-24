# Classes without elements

```php
<?php
use Bartlett\GraphUml;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$generator = new GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new ClassDiagramBuilder(
    $generator,
    $graph,
    [
        'label_format' => 'record',
        'show_constants' => false,
        'show_properties' => false,
        'show_methods' => false,
    ]
);

$builder->createVertexClass(ClassDiagramBuilder::class);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
if (isset($argv[1])) {
    // target folder provided
    $cmdFormat = '%E -T%F %t -o ' . rtrim($argv[1], DIRECTORY_SEPARATOR) . '/without_elements.graphviz.%F';
} else {
    $cmdFormat = '';
}
$target = $generator->createImageFile($graph, $cmdFormat);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

Will output this [graph statements](./without_elements.record.gv).

And png file generated look like :

![Classes without elements](./without_elements.graphviz.png)
