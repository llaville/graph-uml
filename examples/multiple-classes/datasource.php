<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

return function (): Generator
{
    $classes = [
        ClassDiagramBuilder::class,
        GraphVizGenerator::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
