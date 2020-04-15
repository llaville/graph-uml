<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Formatter;

use Bartlett\GraphUml\FormatterInterface;

interface FormatterFactoryInterface
{
    public function createInstance(array $options): FormatterFactoryInterface;

    public function getFormatter(): FormatterInterface;

    public function getType(): string;
}
