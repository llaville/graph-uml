<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @since Release 1.2.0
 * @author Laurent Laville
 */

use Bartlett\GraphUml\Filter\NamespaceFilterInterface;
use Bartlett\GraphUml\Generator\GeneratorInterface;

use Graphp\Graph\Graph;

$callback = function (Generator $vertices, GeneratorInterface $generator, Graph $graph, array $options) {
    foreach ($vertices as $fqcn) {
        $this->createVertexClass($fqcn);

        $namespaceFilter = $options['namespace_filter'];

        if ($namespaceFilter instanceof NamespaceFilterInterface) {
            $cluster = $namespaceFilter->filter($fqcn);
            if (null !== $cluster) {
                $nameParts = explode('\\', $cluster);
                if (count($nameParts) > 2) {
                    if ($nameParts[2] === 'Generator') {
                        $attributes = ['style' => 'filled', 'fillcolor' => 'yellow:blue', 'gradientangle' => 45];
                    } elseif ($nameParts[2] === 'Formatter') {
                        $attributes = ['style' => 'radial', 'fillcolor' => 'yellow:blue', 'gradientangle' => 180];
                    } else {
                        $attributes = [];
                    }
                } else {
                    $attributes = ['bgcolor' => 'lightblue'];
                }

                foreach ($attributes as $attr => $value) {
                    $attribute = sprintf('cluster.%s.graph.%s', $cluster, $attr);
                    $graph->setAttribute($generator->getPrefix() . $attribute, $value);
                }
            }
        }
    }
};
