<!-- markdownlint-disable MD013 -->
# PHP Extensions diagram

In this example, we specify only two extensions available, and these extensions will be rendered in graph.

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

$extensions = get_loaded_extensions(false);

// adding all extensions will result in a huge graph, so just pick 2 random ones
shuffle($extensions);
$extensions = array_slice($extensions, 0, 2);

foreach ($extensions as $extension) {
    $builder->createVertexExtension($extension);
}

// For large graph, orientation is recommended
// https://graphviz.gitlab.io/docs/attrs/rankdir/
$graph->setAttribute($generator->getPrefix() . 'graph.rankdir', 'LR');
// https://graphviz.gitlab.io/docs/attrs/bgcolor/
$graph->setAttribute($generator->getPrefix() . 'graph.bgcolor', 'transparent');
// https://graphviz.gitlab.io/docs/attrs/fillcolor/
$graph->setAttribute($generator->getPrefix() . 'node.fillcolor', '#FEFECE');
// https://graphviz.gitlab.io/docs/attrs/style/
$graph->setAttribute($generator->getPrefix() . 'node.style', 'filled');

// show UML diagram statements
echo $generator->createScript($graph);

// default format is PNG, change it to SVG
$generator->setFormat('svg');

// generate binary image file
$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

Will output this [graph statements](./php_extensions.html.gv).

And image file generated look like :

![PHP Extensions](./php_extensions.graphviz.svg)
