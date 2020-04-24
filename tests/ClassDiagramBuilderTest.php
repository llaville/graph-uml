<?php

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;

class ClassDiagramBuilderTest extends TestCase
{
    private $builder;

    public function setup()
    {
        $generator = new GraphVizGenerator();
        $graph = new Graph();
        $this->builder = new ClassDiagramBuilder($generator, $graph);
    }

    /**
     * Test that the ReflectionExtension class does not reports information about invalid extension.
     *
     * @expectedException  ReflectionException
     */
    public function testExtensionFail()
    {
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
}
