# Single class diagram

```php
<?php
use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$generator = new GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new ClassDiagramBuilder($generator, $graph);

$builder->createVertexClass(ClassDiagramBuilder::class);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
echo $generator->createImageFile($graph) . ' file generated' . PHP_EOL;
```

**NOTE**: Without custom `$options` provided with `ClassDiagramBuilder` class constructor,
all default values are used (see `ClassDiagramBuilderInterface::OPTIONS_DEFAULTS`)

Will output this [graph statements](./single_class.record.gv).

And temporary png file generated look like :

![Single Class UML](./single_class.graphviz.png)
