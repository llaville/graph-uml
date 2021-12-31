<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\ClassDiagramBuilderInterface;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;
use Graphp\GraphViz\GraphViz;

/**
 * @author Laurent Laville
 */
class ClassDiagramBuilderTest extends TestCase
{
    private $builder;
    private $graph;

    public function setup(): void
    {
        $generator = new GraphVizGenerator(new GraphViz());
        $this->graph = new Graph();
        $this->builder = new ClassDiagramBuilder($generator, $this->graph);
    }

    /**
     * Test that the ReflectionExtension class does not reports information about invalid extension.
     */
    public function testExtensionFail()
    {
        $this->expectException(\ReflectionException::class);
        $this->builder->createVertexExtension('unknown');
    }

    /**
     * Test that the ReflectionExtension class reports class information about loaded extension.
     */
    public function testExtensionSuccess()
    {
        $vertex = $this->builder->createVertexExtension('core');

        $this->assertInstanceOf(Vertex::class, $vertex);
    }

    /**
     * Test that the ReflectionClass class does not reports information about invalid class.
     */
    public function testClassFail()
    {
        $this->expectException(\ReflectionException::class);
        $this->builder->createVertexClass('unknown');
    }

    /**
     * Test that the ReflectionClass class reports class information about loaded class instance.
     */
    public function testClassSuccess()
    {
        $vertex = $this->builder->createVertexClass(ClassDiagramBuilder::class);

        $this->assertInstanceOf(Vertex::class, $vertex);
    }

    /**
     * Test that the graph will include all class parents
     *
     * @depends testClassSuccess
     */
    public function testInheritanceSuccess()
    {
        $this->builder->createVertexClass(ClassDiagramBuilder::class);

        $this->assertCount(2, $this->graph->getVertices());
    }

    /**
     * Test that the graph will not include class parents
     *
     * @depends testClassSuccess
     */
    public function testInheritanceWithoutParent()
    {
        $generator = new GraphVizGenerator(new GraphViz());
        $graph = new Graph();
        $builder = new ClassDiagramBuilder($generator, $graph, ['add_parents' => false]);

        $builder->createVertexClass(ClassDiagramBuilder::class);

        $this->assertCount(1, $graph->getVertices());
    }

    /**
     * Test that a class in namespace has a group attribute attached to its corresponding vertex
     *
     * @depends testClassSuccess
     */
    public function testClassInGroup()
    {
        $vertex = $this->builder->createVertexClass(ClassDiagramBuilder::class);

        $reflection = new ReflectionClass(ClassDiagramBuilder::class);

        $this->assertSame($reflection->getNamespaceName(), $vertex->getAttribute('group'));
    }

    /**
     * Test that a class has a stereotype attribute attached to its corresponding vertex
     *
     * @depends testClassSuccess
     */
    public function testClassHasGoodStereotype()
    {
        $vertex = $this->builder->createVertexClass(ClassDiagramBuilder::class);

        $this->assertSame('class', $vertex->getAttribute('stereotype'));
    }

    /**
     * Test that an interface has a stereotype attribute attached to its corresponding vertex
     *
     * @depends testClassSuccess
     */
    public function testInterfaceHasGoodStereotype()
    {
        $vertex = $this->builder->createVertexClass(ClassDiagramBuilderInterface::class);

        $this->assertSame('interface', $vertex->getAttribute('stereotype'));
    }

    /**
     * Test that the graph has edges
     *
     * @depends testClassSuccess
     */
    public function testGraphHasEdges()
    {
        $this->builder->createVertexClass(ClassDiagramBuilder::class);

        $this->assertCount(1, $this->graph->getEdges());
    }

    /**
     * Test that the graph include edges corresponding to vertices connections (class -> interface)
     *
     * @depends testGraphHasEdges
     */
    public function testGraphHasEdgesConnection()
    {
        $vertex = $this->builder->createVertexClass(ClassDiagramBuilder::class);
        $vertices = $this->graph->getVertices();
        $parent = end($vertices);
        $edges = $this->graph->getEdges();

        $edgeFirst = reset($edges);

        $this->assertTrue($edgeFirst->hasVertexStart($vertex));
        $this->assertTrue($edgeFirst->hasVertexTarget($parent));
    }
}
