<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\GraphUml\Formatter;

use ReflectionClass;
use ReflectionExtension;
use ReflectionFunction;
use ReflectionMethod;

/**
 * @author Laurent Laville
 */
interface FormatterInterface
{
    /**
     * Returns format provided
     */
    public function getFormat(): string;

    /**
     * Get label for the given reflection extension module
     */
    public function getLabelExtension(ReflectionExtension $reflection): string;

    /**
     * Get label for the given reflection class
     *
     * @see http://graphviz.org/content/node-shapes#record
     * @see http://graphviz.org/content/attrs#kescString
     */
    public function getLabelClass(ReflectionClass $reflection): string;

    /**
     * Get string describing the constants from the given reflection class or extension module
     */
    public function getLabelConstants(ReflectionClass|ReflectionExtension $reflection): string;

    /**
     * Get string describing the properties from the given reflection class
     */
    public function getLabelProperties(ReflectionClass $reflection): string;

    /**
     * Get string describing the given array of reflection methods / functions
     *
     * @param ReflectionMethod[]|ReflectionFunction[] $functions
     */
    public function getLabelFunctions(array $functions, string $class = ''): string;
}
