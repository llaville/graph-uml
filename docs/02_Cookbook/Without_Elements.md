<!-- markdownlint-disable MD013 -->
# Classes without elements

In this example, we specify only one class, and this class with its direct dependencies will be rendered in graph,
without constants, properties and methods.

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
        'label_format' => 'record',
        'show_constants' => false,
        'show_properties' => false,
        'show_methods' => false,
    ]
);

$builder->createVertexClass(ClassDiagramBuilder::class);

// show UML diagram statements
echo $generator->createScript($graph);

// default format is PNG, change it to SVG
$generator->setFormat('svg');

// generate binary image file
$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

Will output this [graph statements](./without_elements.record.gv).

And image file generated look like :

![Classes without elements](./without_elements.graphviz.svg)
