# bartlett/graph-uml

Generate UML diagrams by reflection for your PHP projects

> Note: This project is in beta stage! Feel free to report any issues you encounter.

## Features

The main features provided by this library are:

* build UML statements of a class diagram
* draw png/svg image formats

## Install

The recommended way to install this library is [through composer](http://getcomposer.org). 
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bartlett/graph-uml
```

Additionally, you'll have to install GraphViz (`dot` executable).
Users of Debian/Ubuntu-based distributions may simply invoke:

```bash
$ sudo apt-get install graphviz
```

while remaining users should install from [GraphViz Download](http://www.graphviz.org/download/) page.

## Quick Start

Once [installed](#install), you can use the following code to draw an UML class
diagram for your existing classes:

```php
<?php
use Bartlett\GraphUml\ClassDiagramBuilder;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

// initialize an empty graph// 
$graph = new Graph();
// and the UML class diagram builder
$builder = new ClassDiagramBuilder(
    $graph,
    [
        'label-format' => 'html',
    ]
);

// identify what class(es) you want to draw. One `createVertexClass()` operation by class.
$builder->createVertexClass(ClassDiagramBuilder::class);

$graphviz = new GraphViz();
// show UML diagram statements
echo $graphviz->createScript($graph);
// default format is PNG
echo $graphviz->createImageFile($graph) . ' file generated' . PHP_EOL;
```

## Documentation



## Credits

This code is a refactored version of [clue/graph-uml](https://github.com/clue/graph-uml) project, licensed under MIT.
