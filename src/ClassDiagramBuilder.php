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

use Webmozart\Assert\Assert;

use Generator;
use ReflectionClass;
use ReflectionException;
use ReflectionExtension;
use function array_filter;
use function class_exists;
use function extension_loaded;
use function interface_exists;
use function is_string;
use function str_replace;
use function str_starts_with;
use const ARRAY_FILTER_USE_KEY;

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
    /** @var array<string, mixed>  */
    private array $options;

    /** @var array<string, Vertex> */
    private array $entities;

    /**
     * ClassDiagramBuilder constructor.
     *
     * @param array<string, mixed> $options
     * @see ClassDiagramBuilderInterface::OPTIONS_DEFAULTS for available options
     */
    public function __construct(private readonly GeneratorInterface $generator, private readonly Graph $graph, array $options = [])
    {
        $attributes = array_filter($options, function ($key) {
            return (str_starts_with($key, 'graph.')
                || str_starts_with($key, 'node.')
                || str_starts_with($key, 'edge.')
                || str_starts_with($key, 'cluster.')
            );
        }, ARRAY_FILTER_USE_KEY);

        $this->graph->setAttributes($attributes);

        $this->options = array_merge(ClassDiagramBuilderInterface::OPTIONS_DEFAULTS, $options);
        $this->generator->setOptions($this->options);
        $this->entities = [];
    }

    /**
     * @inheritDoc
     */
    public function createVertexClass(object|string $source, array $attributes = []): Vertex
    {
        if (is_string($source)) {
            $classExists = class_exists($source);
            $interfaceExists = interface_exists($source);

            // Reflection works without first \ so make sure we don't inject them
            $class = ltrim($source, '\\');

            try {
                $reflection = new ReflectionClass($class);
            } catch (ReflectionException $e) {  // @phpstan-ignore catch.neverThrown
                Assert::true(
                    $classExists || $interfaceExists,
                    'Expected an existing interface/class name. Got: ' . $source
                );
            }
        } else {
            Assert::isInstanceOf($source, ReflectionClass::class);
            $reflection = $source;
            $class = $reflection->getName();
        }

        $vertex = $this->entities[$class] ?? $this->graph->createVertex(['id' => $class]);

        $generatorPrefix = $this->generator->getPrefix();

        $attributePrefix = 'cluster.' . $class . '.node.';
        foreach ($attributes as $option => $value) {
            if (str_starts_with($option, $attributePrefix)) {
                $attributes[str_replace($attributePrefix, '', $option)] = $value;
            }
            unset($attributes[$option]);
        }

        foreach ($attributes as $attribute => $value) {
            $vertex->setAttribute($generatorPrefix . $attribute, $value);
        }

        if (isset($this->entities[$class])) {
            // already exists, use cached version
            return $this->entities[$class];
        }

        $this->entities[$class] = $vertex;

        if ($this->options['add_parents']) {
            $parent = $reflection->getParentClass();
            if ($parent) {
                $parentVertex = $this->createVertexClass($parent, $attributes);
                $this->graph->createEdgeDirected($vertex, $parentVertex)
                    ->setAttribute($generatorPrefix . 'arrowhead', 'empty')
                    ->setAttribute($generatorPrefix . 'style', 'filled')
                ;
            }

            foreach ($this->getInterfaces($reflection) as $interface) {
                $parentVertex = $this->createVertexClass($interface, $attributes);
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
     * @inheritDoc
     */
    public function createVertexExtension(object|string $source, array $attributes = []): Vertex
    {
        if (is_string($source)) {
            $extensionExists = extension_loaded($source);
            Assert::true($extensionExists, 'Expected an existing extension name. Got: ' . $source);
            $reflection = new ReflectionExtension($source);
        } else {
            Assert::isInstanceOf($source, ReflectionExtension::class);
            $reflection = $source;
            $extension = $reflection->getName();
        }

        $vertex = $this->graph->createVertex(['id' => $source]);

        $generatorPrefix = $this->generator->getPrefix();

        foreach ($attributes as $attribute => $value) {
            $vertex->setAttribute($generatorPrefix . $attribute, $value);
        }

        $formatterType = $this->generator->getFormatter()->getFormat();

        $vertex->setAttribute(
            $generatorPrefix . 'shape',
            ($formatterType === 'html' ? 'none' : 'record')
        );
        $vertex->setAttribute(
            $generatorPrefix . 'label_' . $formatterType,
            $this->generator->getLabelExtension($reflection)
        );
        $vertex->setAttribute('group', 'PHP');
        $vertex->setAttribute('stereotype', 'extension');

        return $vertex;
    }

    public function createVerticesFromCallable(callable $callback, Generator $vertices): void
    {
        $closure = $callback(...);
        $closure = $closure->bindTo($this);
        $closure($vertices, $this->generator, $this->graph, $this->options);
    }

    /**
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
