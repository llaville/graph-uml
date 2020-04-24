<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\FormatterInterface;

use Graphp\Graph\Graph;

interface GeneratorInterface
{
    public function getFormatter(): FormatterInterface;

    public function getName(): string;

    public function render(Graph $graph): string;
}
