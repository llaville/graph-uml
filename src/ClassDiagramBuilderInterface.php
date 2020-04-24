<?php

declare(strict_types=1);

namespace Bartlett\GraphUml;

use Graphp\Graph\Vertex;

interface ClassDiagramBuilderInterface
{
    public const OPTIONS_DEFAULTS = [
        // string to indent graph statement parts
        'indent-string' => '  ',
        // whether to use html or record formatted labels (graphviz specific feature)
        'label-format' => 'record',
        // whether to only show methods/properties that are actually defined in this class (and not those merely inherited from base)
        'only-self' => true,
        // whether to also show private methods/properties
        'show-private' => true,
        // whether to also show protected methods/properties
        'show-protected' => true,
        // whether to show class constants as readonly static variables (or just omit them completely)
        'show-constants' => true,
        // whether to show add parent classes or interfaces
        'add-parents' => true,
    ];

    public function createVertexClass($class): Vertex;

    public function createVertexExtension($extension): Vertex;
}
