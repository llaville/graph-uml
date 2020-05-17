<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\Formatter\FormatterInterface;

use Graphp\Graph\Graph;

use ReflectionClass;
use ReflectionExtension;

interface GeneratorInterface
{
    public function setOptions(array $values): void;

    public function getFormatter(): FormatterInterface;

    public function getName(): string;

    public function getPrefix(): string;

    public function getLabelClass(ReflectionClass $reflection): string;

    public function getLabelExtension(ReflectionExtension $reflection): string;

    public function setExecutable($executable): void;

    public function setFormat(string $format): void;

    public function createScript(Graph $graph): string;

    public function createImageFile(Graph $graph, string $cmdFormat): string;
}
