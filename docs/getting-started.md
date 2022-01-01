<!-- markdownlint-disable MD013 -->
# Getting started

## Requirements

* PHP 7.1.3 or greater
* [graphp/graph](https://github.com/graphp/graph) package from master branch (considered as future stable v1.0.0)
* [graphp/graphviz](https://github.com/graphp/graphviz) package from master branch (considered as future stable v1.0.0)

![GraPHP Composer](./graph-composer.svg)

Generated with [fork](https://github.com/markuspoerschke/graph-composer/tree/add-options-to-exclude) of [clue/graph-composer](https://github.com/clue/graph-composer).
Learn more about this fork on [PR request](https://github.com/clue/graph-composer/pull/45).

## Installation

### With Composer

The recommended way to install this library is [through composer](http://getcomposer.org).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```shell
composer require bartlett/graph-uml
```

### With Git

The Graph-UML can be directly used from [GitHub](https://github.com/llaville/graph-uml.git)
by cloning the repository into a directory of your choice.

```shell
git clone https://github.com/llaville/graph-uml.git
```

Additionally, you'll have to install GraphViz (`dot` executable).
Users of Debian/Ubuntu-based distributions may simply invoke:

```bash
sudo apt update
sudo apt-get install graphviz -y
```

while remaining users should install from [GraphViz Download](http://www.graphviz.org/download/) page.
