<!-- markdownlint-disable MD013 -->
# PHP Extensions diagram

In this example, we specify only two extensions available, and these extensions will be rendered in graph.

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
    'node.fillcolor' => '#FEFECE',
];

$datasource = [
    'lzf',
    'yaml',
];

$generator = new GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new ClassDiagramBuilder($generator, $graph, $options);

foreach ($datasource as $i => $extension) {
    $attributes = ($i === 0) ? ['fillcolor' => 'burlywood3'] : [];
    $builder->createVertexExtension($extension, $attributes);
}

// show UML diagram statements
echo $generator->createScript($graph);

// default format is PNG, change it to SVG
$generator->setFormat('svg');

// generate binary image file
$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

Will output this [graph statements](../assets/images/php-extensions.html.gv).

And image file generated look like :

![PHP Extensions](../assets/images/php-extensions.graphviz.svg)
