<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

return [
    'label_format' => 'html',
    'show_constants' => false,
    'show_private' => false,
    'show_protected' => false,
    'graph.rankdir' => 'LR',
    'graph.bgcolor' => 'transparent',
    'node.fillcolor' => '#FECECE',
    'node.style' => 'filled',
    // @link https://graphviz.gitlab.io/docs/attr-types/colorList/
    'cluster.Bartlett\\GraphUml\\Formatter.graph.bgcolor' => 'burlywood1',
    'cluster.Bartlett\\GraphUml\\Formatter\\FormatterInterface.node.fillcolor' => 'burlywood3',
];
