<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Formatter;

use Bartlett\GraphUml\FormatterInterface;

abstract class AbstractFormatterFactory implements FormatterFactoryInterface
{
    protected $formatter;

    protected $options;

    public function __construct(array $options = [])
    {
        $this->formatter = strtolower($options['label-format']);
        $this->options = $options;
    }

    public function createInstance(array $options): FormatterFactoryInterface
    {
        return new static($options);
    }

    abstract public function getFormatter(): FormatterInterface;

    public function getType(): string
    {
        return $this->formatter;
    }
}
