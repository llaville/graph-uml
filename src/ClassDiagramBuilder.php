<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\GraphUml;

use Bartlett\GraphUml\Generator\GeneratorInterface;

use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;

use ReflectionClass;
use ReflectionExtension;

/**
 * UML class diagram builder
 *
 * @link https://www.uml-diagrams.org/class-diagrams-overview.html
 * @link http://www.ffnn.nl/pages/articles/media/uml-diagrams-using-graphviz-dot.php
 * @link http://www.holub.com/goodies/uml/
 *
 * @author Laurent Laville
 */
final class ClassDiagramBuilder implements ClassDiagramBuilderInterface
{
    /**
     * Graph instance to operate on
     * @var Graph
     */
    private $graph;

    /** @var array<string, mixed>  */
    private $options;

    /** @var GeneratorInterface  */
    private $generator;

    /** @var array<string, Vertex> */
    private $entities;

    /**
     * ClassDiagramBuilder constructor.
     *
     * @param GeneratorInterface $generator
     * @param Graph $graph
     * @param array<string, mixed> $options
     * @see ClassDiagramBuilderInterface::OPTIONS_DEFAULTS for available options
     */
    public function __construct(GeneratorInterface $generator, Graph $graph, array $options = [])
    {
        $this->graph = $graph;
        $this->options = array_merge(ClassDiagramBuilderInterface::OPTIONS_DEFAULTS, $options);
        $this->generator = $generator;
        $this->generator->setOptions($this->options);
        $this->entities = [];
    }

    /**
     * {@inheritDoc}
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

        if (isset($this->entities[$class])) {
            return $this->entities[$class];
        }

        $generatorPrefix = $this->generator->getPrefix();

        $vertex = $this->graph->createVertex(['id' => $class]);
        $this->entities[$class] = $vertex;

        if ($this->options['add_parents']) {
            $parent = $reflection->getParentClass();
            if ($parent) {
                $parentVertex = $this->createVertexClass($parent);
                $this->graph->createEdgeDirected($vertex, $parentVertex)
                    ->setAttribute($generatorPrefix . 'arrowhead', 'empty')
                    ->setAttribute($generatorPrefix . 'style', 'filled')
                ;
            }

            foreach ($this->getInterfaces($reflection) as $interface) {
                $parentVertex = $this->createVertexClass($interface);
                $this->graph->createEdgeDirected($vertex, $parentVertex)
                    ->setAttribute($generatorPrefix . 'arrowhead', 'empty')
                    ->setAttribute($generatorPrefix . 'style', 'dashed')
                ;
            }
        }

        $formatterType = $this->generator->getFormatter()->getFormat();

        $vertex->setAttribute(
            $generatorPrefix . 'shape',
            ($formatterType === 'html' ? 'none' : 'record')
        );
        $vertex->setAttribute(
            $generatorPrefix . 'label_' . $formatterType,
            $this->generator->getLabelClass($reflection)
        );

        if ($reflection->inNamespace()) {
            $vertex->setAttribute('group', $reflection->getNamespaceName());
        }

        $vertex->setAttribute('stereotype', $reflection->isInterface() ? 'interface' : 'class');

        return $vertex;
    }

    /**
     * {@inheritDoc}
     */
    public function createVertexExtension($extension): Vertex
    {
        if ($extension instanceof ReflectionExtension) {
            $reflection = $extension;
            $extension = $reflection->getName();
        } else {
            $reflection = new ReflectionExtension($extension);
        }

        $vertex = $this->graph->createVertex(['id' => $extension]);

        $formatterType = $this->generator->getFormatter()->getFormat();
        $generatorPrefix = $this->generator->getPrefix();

        $vertex->setAttribute(
            $generatorPrefix . 'shape',
            ($formatterType === 'html' ? 'none' : 'record')
        );
        $vertex->setAttribute(
            $generatorPrefix . 'label_' . $formatterType,
            $this->generator->getLabelExtension($reflection)
        );
        $vertex->setAttribute('group', 'PHP Extensions');

        return $vertex;
    }

    /**
     * @param ReflectionClass $reflection
     * @return ReflectionClass[]
     */
    private function getInterfaces(ReflectionClass $reflection): array
    {
        // a list of all interfaces implemented explicitly or implicitly
        $interfaces = $reflection->getInterfaces();

        // remove each interface already implemented by the parent class (if any)
        $parent = $reflection->getParentClass();
        if ($parent) {
            foreach ($parent->getInterfaceNames() as $interface) {
                unset($interfaces[$interface]);
            }
        }

        // remove each interface already implemented by any of the inherited interfaces
        foreach ($interfaces as $interface) {
            foreach ($interface->getInterfaceNames() as $contract) {
                unset($interfaces[$contract]);
            }
        }

        return $interfaces;
    }
}
