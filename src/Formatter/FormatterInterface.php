<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Formatter;

use ReflectionClass;
use ReflectionExtension;
use ReflectionFunction;
use ReflectionMethod;

interface FormatterInterface
{
    /**
     * Returns format provided
     *
     * @return string
     */
    public function getFormat(): string;

    /**
     * Get label for the given reflection extension module
     *
     * @param ReflectionExtension $reflection
     * @return string
     */
    public function getLabelExtension(ReflectionExtension $reflection): string;

    /**
     * Get label for the given reflection class
     *
     * @param ReflectionClass $reflection
     * @return string
     *
     * @see http://graphviz.org/content/node-shapes#record
     * @see http://graphviz.org/content/attrs#kescString
     */
    public function getLabelClass(ReflectionClass $reflection): string;

    /**
     * Get string describing the constants from the given reflection class or extension module
     *
     * @param ReflectionClass|ReflectionExtension $reflection
     * @return string
     */
    public function getLabelConstants($reflection): string;

    /**
     * Get string describing the properties from the given reflection class
     *
     * @param ReflectionClass $reflection
     * @return string
     */
    public function getLabelProperties(ReflectionClass $reflection): string;

    /**
     * Get string describing the given array of reflection methods / functions
     *
     * @param ReflectionMethod[]|ReflectionFunction[] $functions
     * @param string|null $class
     * @return string
     */
    public function getLabelFunctions(array $functions, string $class = null): string;
}
