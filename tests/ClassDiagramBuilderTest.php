<?php

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;
use Graphp\GraphViz\GraphViz;

class ClassDiagramBuilderTest extends TestCase
{
    private $builder;

    public function setup(): void
    {
        $generator = new GraphVizGenerator(new GraphViz());
        $graph = new Graph();
        $this->builder = new ClassDiagramBuilder($generator, $graph);
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

}
