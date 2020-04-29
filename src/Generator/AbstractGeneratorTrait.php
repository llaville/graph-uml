<?php
declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\Formatter\FormatterInterface;

use ReflectionClass;
use ReflectionExtension;

trait AbstractGeneratorTrait
{
    protected $options;

    abstract public function getFormatter(): FormatterInterface;

    public function setOptions(array $values): void
    {
        $this->options = $values;
    }

    public function getLabel(ReflectionClass $reflection): string
    {
        return $this->getFormatter()->getLabelClass($reflection);
    }

    public function getExtension(ReflectionExtension $reflection): string
    {
        return $this->getFormatter()->getLabelExtension($reflection);
    }
}
