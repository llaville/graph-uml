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
if (isset($argv[1])) {
    // target folder provided
    $cmdFormat = '%E -T%F %t -o ' . rtrim($argv[1], DIRECTORY_SEPARATOR) . '/single_class.graphviz.%F';
} else {
    $cmdFormat = '';
}
$target = $generator->createImageFile($graph, $cmdFormat);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
```

**NOTE**: Without custom `$options` provided with `ClassDiagramBuilder` class constructor,
all default values are used (see `ClassDiagramBuilderInterface::OPTIONS_DEFAULTS`)

Will output this [graph statements](./single_class.record.gv).

And png file generated look like :

![Single Class UML](./single_class.graphviz.png)
