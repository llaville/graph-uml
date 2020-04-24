<?php
declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\FormatterInterface;

use ReflectionClass;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionProperty;

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

    protected function getVisibilityChar($reflection): string
    {
        if ($reflection->isPrivate()) {
            return '-';
        }
        if ($reflection->isProtected()) {
            return '#';
        }
        return '+';
    }

    protected function getModifierChar($reflection): string
    {
        if ($reflection instanceof ReflectionProperty) {
            if ($reflection->isStatic()) {
                return '<u>%s</u>';
            }
        } elseif ($reflection instanceof ReflectionMethod) {
            if ($reflection->isStatic()) {
                return '<u>%s()</u>';
            }
            if ($reflection->isAbstract()) {
                return '<i>%s()</i>';
            }
            return '%s()';
        }
        return '%s';
    }
}
