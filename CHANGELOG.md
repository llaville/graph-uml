<!-- markdownlint-disable MD013 MD024 -->
# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

## [1.2.2] - 2022-01-04

### Fixed

- `graphp/graph` constraint to specific commit compatible with patch provided (see `patches` directory)
- `graphp/graphviz` constraint to specific commit compatible with patch provided (see `patches` directory)

## [1.2.1] - 2022-01-02

### Changed

- `Bartlett\GraphUml\ClassDiagramBuilderInterface::createVertexExtension` have a second optional parameter to specify default vertex attributes

### Fixed

- cluster of vertices for PHP extension graph is now named 'PHP'
- stereotype for PHP extension is now 'extension'
- default attributes on interface inheritance is now applied

## [1.2.0] - 2022-01-01

### Added

- ability to filter source on namespace (your class should implement `Bartlett\GraphUml\Filter\NamespaceFilterInterface`)
: default is none filter
- resources to generate dynamically all UML graphs for documentation (see `.github/workflows/gh-pages.yml`)
- `Bartlett\GraphUml\ClassDiagramBuilder::createVerticesFromCallable` to generate a graph with a subset of vertices, from a callable

## [1.1.0] - 2021-12-24

### Added

- example script `examples/multiple_classes/graphviz.php` already shown in documentation

### Changed

- `Bartlett\GraphUml\Generator\AbstractGenerator::createImageFile` returns now the command used to generate image file rather than temporary file
- update all examples to generate image in target folder, if provided as first argument

## [1.0.1] - 2021-12-01

### Added

- `AbstractFormatter::getParameterDefaultValue` to handle default value of optional parameters in function signatures.

### Fixed

- return type checks in RecordFormatter.

## [1.0.0] - 2021-12-01

### Added

- GitHub workflow to build/deploy documentation with mkdocs/[mkdocs-material](https://github.com/squidfunk/mkdocs-material)
- GitHub workflow to run [Mega-Linter](https://github.com/megalinter/megalinter) QA tool

### Changed

- switch LICENSE from BSD 3-Clause "New" or "Revised" License to MIT

### Fixed

- Issue [#2](https://github.com/llaville/graph-uml/issues/2) -- Cannot install through composer.

## [1.0.0-rc.3] - 2021-11-20

### Changed

- Allow installation with PHP 8
- Patch `graphp/graphviz` package with <https://github.com/cweagans/composer-patches> rather than using forks with branches

If you need a good introduction about vendor patches,
read this excellent article <https://tomasvotruba.com/blog/2020/07/02/how-to-patch-package-in-vendor-yet-allow-its-updates/>

## [1.0.0-rc.2] - 2020-09-10

### Fixed

- synchronize with `graphp/graph` master branch (still under development) and fix it to commit [214de45](https://github.com/graphp/graph/commit/214de4572f0fa8a452addcf6135f87bfd3dec4ab)
due to new feature
  - [Remove `Vertices` and `Edges` collection classes, use plain arrays instead](https://github.com/graphp/graph/pull/195)

## [1.0.0-rc.1] - 2020-05-30

### Fixed

- usage of `getPrefix()` method

## [1.0.0-beta.2] - 2020-05-18

### Fixed

- `AbstractFormatter::getType()` will return either string or null

## [1.0.0-beta.1] - 2020-05-18

### Added

- add new `getPrefix()` method to **GeneratorInterface** that suggest attribute public (only if necessary: see **GraphViz** class)

### Changed

- change case of options names
from [Kebab case](https://en.wikipedia.org/wiki/Letter_case#Special_case_styles) to [Snake case](https://en.wikipedia.org/wiki/Snake_case)

### Fixed

- undefined `$class` variable  in `RecordFormatter::getLabelProperties()`
- minor other QA (thanks to all tools available in <https://github.com/jakzal/phpqa>)

## [0.8.0] - 2020-05-14

### Changed

- better support to subgraph cluster attributes
  - see commit [a4b6b57](https://github.com/llaville/graphviz/commit/a4b6b5747375ade8e21dbce5773cfdc0326b5b32) of **llaville/graphviz**
  - see also `examples/graphuml-architecture/graphviz.php` to learn how to use

## [0.7.0] - 2020-05-12

### Added

- introduces `show-properties` and `show-methods` options implemented in both html and record formatters

### Fixed

- all examples
- Html Record formatter

## [0.6.2] - 2020-05-11

### Changed

- avoid blank line for when there are no entity constants in Html formatter of Graphviz generator
- fixed Html formatter of Graphviz generator to display nicely constants of each Entity

## [0.6.1] - 2020-05-09

### Fixed

- escape special character in `AbstractFormatter` when casting default value (function parameters, constants)

## [0.6.0] - 2020-05-01

### Added

- ability to personalize subgraph cluster attributes of GraphViz (namespace elements)

### Changed

- `GeneratorInterface` contract as evolved to make image file, and script.
- `AbstractGenerator` class replaced the `AbstractGeneratorTrait` for generator composition rather than inheritance.

## [0.5.1] - 2020-04-28

### Fixed

- avoids duplicated entities in a graph.

## [0.5.0] - 2020-04-27

### Added

- add UML `stereotype` (<https://www.uml-diagrams.org/stereotype.html>) as attribute of each vertex
- add `getFormat()` method in Formatter contract

### Fixed

- be sure to have always a `style` attribute for each edges
- fix all examples by following Generator contract and use `render()` instead of `createScript()`

## [0.4.0] - 2020-04-26

- introduces full documentation in markdown format (see `docs/` folder).
- changed namespace of `FormatterInterface`.

## [0.3.0] - 2020-04-24

- introduces first series of unit tests about `createVertexExtension`
- introduces Graph Generator feature to allow more back-end than just GraphViz in future releases

## [0.2.0] - 2020-04-17

- use native PHP API [ReflectionType](https://www.php.net/manual/en/class.reflectiontype.php) instead of docblock annotations.

## [0.1.0] - 2020-04-15

preview release features include :

- build UML [Class diagram](https://en.wikipedia.org/wiki/Class_diagram)
- two formatters are provided by default: `record`, and `html`
