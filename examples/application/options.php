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
    'show_constants' => false,
    'show_private' => false,
    'show_protected' => false,
    'graph.rankdir' => 'LR',
    // @link https://graphviz.gitlab.io/docs/attr-types/colorList/
    'cluster.Bartlett\\GraphUml.graph.bgcolor' => 'lightblue',
    'cluster.Bartlett\\GraphUml\\Generator.graph.style' => 'filled',
    'cluster.Bartlett\\GraphUml\\Generator.graph.fillcolor' => 'yellow:blue',
    'cluster.Bartlett\\GraphUml\\Generator.graph.gradientangle' => 45,
    'cluster.Bartlett\\GraphUml\\Formatter.graph.style' => 'radial',
    'cluster.Bartlett\\GraphUml\\Formatter.graph.fillcolor' => 'yellow:blue',
    'cluster.Bartlett\\GraphUml\\Formatter.graph.gradientangle' => 180,
];
