# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

## [0.6.2] - 2020-05-11

### Changed

* avoid blank line for when there are no entity constants in Html formatter of Graphviz generator
* fixed Html formatter of Graphviz generator to display nicely constants of each Entity

## [0.6.1] - 2020-05-09

### Fixed

* escape special character in `AbstractFormatter` when casting default value (function parameters, constants)

## [0.6.0] - 2020-05-01

### Added

* ability to personalize subgraph cluster attributes of GraphViz (namespace elements)

### Changed

* `GeneratorInterface` contract as evolved to make image file, and script.
* `AbstractGenerator` class replaced the `AbstractGeneratorTrait` for generator composition rather than inheritance.

## [0.5.1] - 2020-04-28

### Fixed

* avoids duplicated entities in a graph.

## [0.5.0] - 2020-04-27

### Added

* add UML `stereotype` (https://www.uml-diagrams.org/stereotype.html) as attribute of each vertex
* add `getFormat()` method in Formatter contract

### Fixed

* be sure to have always a `style` attribute for each edges
* fix all examples by following Generator contract and use `render()` instead of `createScript()`

## [0.4.0] - 2020-04-26

* introduces full documentation in markdown format (see `docs/` folder).
* changed namespace of `FormatterInterface`.

## [0.3.0] - 2020-04-24

* introduces first series of unit tests about `createVertexExtension`
* introduces Graph Generator feature to allow more back-end than just GraphViz in future releases

## [0.2.0] - 2020-04-17

* use native PHP API [ReflectionType](https://www.php.net/manual/en/class.reflectiontype.php) instead of docblock annotations.

## [0.1.0] - 2020-04-15

preview release features include :

* build UML [Class diagram](https://en.wikipedia.org/wiki/Class_diagram)
* two formatters are provided by default: `record`, and `html`
