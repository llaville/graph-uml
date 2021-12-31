<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\GraphUml;

use Graphp\Graph\Vertex;

use ReflectionClass;
use ReflectionException;
use ReflectionExtension;

/**
 * @author Laurent Laville
 */
interface ClassDiagramBuilderInterface
{
    public const OPTIONS_DEFAULTS = [
        // string to indent graph statement parts
        'indent_string' => '  ',
        // whether to use html or record formatted labels (graphviz specific feature)
        'label_format' => 'record',
        // whether to only show methods/properties that are actually defined in this class (and not those merely inherited from base)
        'only_self' => true,
        // whether to also show private methods/properties
        'show_private' => true,
        // whether to also show protected methods/properties
        'show_protected' => true,
        // whether to show class constants as readonly static variables (or just omit them completely)
        'show_constants' => true,
        // whether to show class properties
        'show_properties' => true,
        // whether to show class or interface methods
        'show_methods' => true,
        // whether to show add parent classes or interfaces
        'add_parents' => true,
    ];

    /**
     * @param ReflectionClass|string $class
     * @return Vertex
     * @throws ReflectionException
     */
    public function createVertexClass($class): Vertex;

    /**
     * @param ReflectionExtension|string $extension
     * @return Vertex
     * @throws ReflectionException
     */
    public function createVertexExtension($extension): Vertex;
}
