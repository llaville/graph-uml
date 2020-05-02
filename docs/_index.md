
### Features

The main features provided by this library are:

* build UML statements of a class diagram
* draw png/svg image formats

Currently, the following language features are supported:

* Property and method visibility
* Static properties and methods
* Method return types natively and from doc comment
* Parameter types from type hinting and doc comment
* Parameter default values
* Class constants with value
* Property types from doc comment
* Property default values
* Implemented interfaces and parent classes
* Abstract classes

### Install

The recommended way to install this library is [through composer](http://getcomposer.org).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bartlett/graph-uml
```

![Graph Composer](./graph_composer.png)

Additionally, you'll have to install GraphViz (`dot` executable).
Users of Debian/Ubuntu-based distributions may simply invoke:

```bash
$ sudo apt-get install graphviz
```

while remaining users should install from [GraphViz Download](http://www.graphviz.org/download/) page.
