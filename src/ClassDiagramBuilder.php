<?php

declare(strict_types=1);

namespace Bartlett\GraphUml;

use Bartlett\GraphUml\Formatter\FormatterFactory;

use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;

use ReflectionClass;
use ReflectionExtension;
use ReflectionException;

/**
 * UML class diagram builder
 *
 * @link https://www.uml-diagrams.org/class-diagrams-overview.html
 * @link http://www.ffnn.nl/pages/articles/media/uml-diagrams-using-graphviz-dot.php
 * @link http://www.holub.com/goodies/uml/
 */
final class ClassDiagramBuilder implements ClassDiagramBuilderInterface
{
    /**
     * Graph instance to operate on
     * @var Graph
     */
    private $graph;

    /** @var array  */
    private $options;

    /** @var FormatterFactory  */
    private $formatterFactory;

    /**
     * ClassDiagramBuilder constructor.
     *
     * @param Graph $graph
     * @param array $options
     * @see ClassDiagramBuilderInterface::OPTIONS_DEFAULTS for available options
     */
    public function __construct(Graph $graph, array $options = [])
    {
        $this->graph = $graph;
        $this->options = array_merge(ClassDiagramBuilderInterface::OPTIONS_DEFAULTS, $options);
        $this->formatterFactory = new FormatterFactory($this->options);
    }

    /**
     * @param ReflectionClass|string $class
     * @return Vertex
     * @throws ReflectionException
     */
    public function createVertexClass($class): Vertex
    {
        if ($class instanceof ReflectionClass) {
            $reflection = $class;
            $class = $reflection->getName();
        } else {
            // Reflection works without first \ so make sure we don't inject them
            $class = ltrim($class, '\\');
            $reflection = new ReflectionClass($class);
        }
        $vertex = $this->graph->createVertex(['id' => $class]);
        if ($this->options['add-parents']) {
            $parent = $reflection->getParentClass();
            if ($parent) {
                $parentVertex = $this->createVertexClass($parent);
                $this->graph->createEdgeDirected($vertex, $parentVertex)->setAttribute('graphviz.arrowhead', 'empty');
            }

            foreach ($this->getInterfaces($reflection) as $interface) {
                $parentVertex = $this->createVertexClass($interface);
                $this->graph->createEdgeDirected($vertex, $parentVertex)->setAttribute('graphviz.arrowhead', 'empty')->setAttribute('graphviz.style', 'dashed');
            }
        }

        $formatter = $this->formatterFactory->getFormatter();
        $formatterType = $this->formatterFactory->getType();

        $vertex->setAttribute('graphviz.shape', ($formatterType === 'html' ? 'none' : 'record'));
        $vertex->setAttribute('graphviz.label_' . $formatterType, $formatter->getLabelClass($reflection));

        if ($reflection->inNamespace()) {
            $vertex->setAttribute('group', $reflection->getNamespaceName());
        }

        return $vertex;
    }

    /**
     * @param ReflectionExtension|string $extension
     * @return Vertex
     * @throws ReflectionException
     */
    public function createVertexExtension($extension): Vertex
    {
        if ($extension instanceof ReflectionExtension) {
            $reflection = $extension;
            $extension = $reflection->getName();
        } else {
            $reflection = new ReflectionExtension($extension);
        }

        $formatter = $this->formatterFactory->getFormatter();
        $formatterType = $this->formatterFactory->getType();

        $vertex = $this->graph->createVertex(['id' => $extension]);

        $vertex->setAttribute('graphviz.shape', ($formatterType === 'html' ? 'none' : 'record'));
        $vertex->setAttribute('graphviz.label_' . $this->formatterFactory->getType(), $formatter->getLabelExtension($reflection));
        $vertex->setAttribute('group', 'PHP Extensions');

        return $vertex;
    }

    private function getInterfaces(ReflectionClass $reflection): array
    {
        // a list of all interfaces implemented explicitly or implicitly
        $interfaces = $reflection->getInterfaces();

        // remove each interface already implemented by the parent class (if any)
        $parent = $reflection->getParentClass();
        if ($parent) {
            foreach ($parent->getInterfaceNames() as $in) {
                unset($interfaces[$in]);
            }
        }

        // remove each interface already implemented by any of the inherited interfaces
        foreach ($interfaces as $if) {
            foreach ($if->getInterfaceNames() as $in) {
                unset($interfaces[$in]);
            }
        }

        return $interfaces;
    }
}
