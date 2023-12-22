<!-- markdownlint-disable MD013 -->
# Single class diagram

In this example, we specify only one class, and this class with its direct dependencies will be rendered in graph.

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
];

$builder->createVertexClass(ClassDiagramBuilder::class);

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

Will output this [graph statements](../assets/images/single-class.html.gv).

And image file generated look like :

![Single Class UML](../assets/images/single-class.graphviz.svg)
