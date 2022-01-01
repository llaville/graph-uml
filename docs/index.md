<!-- markdownlint-disable MD013 -->
# About

Generate UML diagrams by reflection for your PHP projects.

![Graph UML Application](./application.graphviz.svg)

## Features

The main features provided by this library are:

* build UML statements of a class diagram
* build image in one of the [supported formats](https://graphviz.gitlab.io/_pages/doc/info/output.html) with local `dot` executable (when **GraphVizGenerator** is used)

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
