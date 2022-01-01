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
        $attrs = [];

        $namespaceFilter = $options['namespace_filter'];
        if ($namespaceFilter instanceof NamespaceFilterInterface) {
            $cluster = $namespaceFilter->filter($fqcn);
            if (null !== $cluster) {
                if ($namespaceFilter->getShortClass() == 'GeneratorInterface') {
                    // highlight this specific element
                    $attrs = ['fillcolor' => 'burlywood3'];
                }
                $graph->setAttribute(
                    $generator->getPrefix() . sprintf('cluster.%s.graph.bgcolor', $cluster),
                    'burlywood1'
                );
            }
        }

        $this->createVertexClass($fqcn, $attrs);
    }
};
