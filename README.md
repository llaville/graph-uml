[![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/graph-uml.svg?style=flat-square)](https://packagist.org/packages/bartlett/graph-uml)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)

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
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

// use GraphViz as back-end generator
$generator = new GraphVizGenerator(new GraphViz());
// initialize an empty graph
$graph = new Graph();
// and the UML class diagram builder
$builder = new ClassDiagramBuilder(
    $generator,
    $graph,
    [
        'label-format' => 'html',
    ]
);

// identify what class(es) you want to draw. One `createVertexClass()` operation by class.
$builder->createVertexClass(ClassDiagramBuilder::class);

// show UML diagram statements
echo $generator->createScript($graph);
// default format is PNG
echo $generator->createImageFile($graph) . ' file generated' . PHP_EOL;
```

## Documentation

Full documentation is written in MarkDown format, and HTML export is possible with [Daux.io](https://github.com/dauxio/daux.io)

## Resources

* https://github.com/llaville/graph-plantuml-generator to draw UML diagrams in [PlantUML](https://plantuml.com/) format.
* http://www.graphviz.org/
* Let's Graphviz it Online https://github.com/dreampuf/GraphvizOnline
* [The Top 55 Graphviz Open Source Projects](https://awesomeopensource.com/projects/graphviz)

## Contributors

* Laurent Laville (Lead Developer)

[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/0)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/0)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/1)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/1)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/2)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/2)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/3)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/3)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/4)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/4)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/5)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/5)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/6)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/6)
[![](https://sourcerer.io/fame/llaville/llaville/graph-uml/images/7)](https://sourcerer.io/fame/llaville/llaville/graph-uml/links/7)

## Credits

This code is a refactored version of [clue/graph-uml](https://github.com/clue/graph-uml) project, licensed under MIT.
