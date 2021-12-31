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
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use function gettype;

/**
 * @author Laurent Laville
 */
final class RecordFormatter extends AbstractFormatter implements FormatterInterface
{
    public function getLabelExtension(ReflectionExtension $reflection): string
    {
        $label = '{';
        $label .= '«extension»\\n';
        $label .= $this->escape($reflection->getName());
        $label .= '|';
        $label .= $this->getLabelConstants($reflection);
        $label .= '|';
        $label .= $this->getLabelFunctions($reflection->getFunctions());
        $label .= '}';

        return $label;
    }

    public function getLabelClass(ReflectionClass $reflection): string
    {
        $class = $reflection->getName();

        // start 'over'
        $label = '{';

        $label .= $this->getStereotype($reflection);
        $label .= self::EOL;

        // new cell
        $label .= $this->escape($class) . '|';

        $label .= $this->getLabelConstants($reflection);

        $label .= $this->getLabelProperties($reflection);

        // new cell
        $label .= '|';

        $label .= $this->getLabelFunctions($reflection->getMethods(), $class);

        // end 'over'
        $label .= '}';

        return $label;
    }

    public function getLabelConstants($reflection): string
    {
        $label = '';

        if (!$this->options['show_constants']) {
            return $label;
        }

        $parent = ($reflection instanceof ReflectionClass) ? $reflection->getParentClass() : false;

        foreach ($reflection->getConstants() as $name => $value) {
            if ($this->options['only_self'] && $parent && $parent->getConstant($name) === $value) {
                continue;
            }

            $label .= '+ «static» '
                . $this->escape($name)
                . ' : '
                . $this->escape($this->getType(gettype($value)))
                . ' = '
                . $this->getCasted($value) . ' \\{readOnly\\}\\l'
            ;
        }

        return $label;
    }

    public function getLabelProperties(ReflectionClass $reflection): string
    {
        if (!$this->options['show_properties']) {
            return '';
        }

        $defaults = $reflection->getDefaultProperties();
        $label = '';

        foreach ($reflection->getProperties() as $property) {
            if (
                $this->options['only_self']
                && $property->getDeclaringClass()->getName() !== $reflection->getName()
            ) {
                continue;
            }

            if (!$this->isVisible($property)) {
                continue;
            }

            $label .= $this->visibility($property);
            if ($property->isStatic()) {
                $label .= ' «static»';
            }
            $label .= ' ' . $this->escape($property->getName());

            $type = $this->getDocBlockVar($property);
            if ($type !== null) {
                $label .= ' : ' . $this->escape($type);
            }

            // only show non-NULL values
            if (isset($defaults[$property->getName()])) {
                $label .= ' = ' . $this->getCasted($defaults[$property->getName()]);
            }

            // align this line to the left
            $label .= '\\l';
        }

        return $label;
    }

    public function getLabelFunctions(array $functions, string $class = ''): string
    {
        if ($class && !$this->options['show_methods']) {
            return '';
        }

        $label = '';

        foreach ($functions as $method) {
            if ($method instanceof ReflectionMethod) {
                // method not defined in this class (inherited from parent), so skip
                if ($this->options['only_self'] && $method->getDeclaringClass()->getName() !== $class) {
                    continue;
                }

                if (!$this->isVisible($method)) {
                    continue;
                }

                $label .= $this->visibility($method);

                if ($method->isAbstract()) {
                    $label .= ' «abstract»';
                }
                if ($method->isStatic()) {
                    $label .= ' «static»';
                }
            } else {
                // ReflectionFunction does not define any of the above accessors
                // simply pretend this is a "normal" public method
                $label .= '+ ';
            }
            $label .= ' ' . $this->escape($method->getName()) . '(';

            $firstParam = true;
            foreach ($method->getParameters() as $parameter) {
                /** @var ReflectionParameter $parameter */
                if ($firstParam) {
                    $firstParam = false;
                } else {
                    $label .= ', ';
                }

                if ($parameter->isPassedByReference()) {
                    $label .= 'inout ';
                }

                $label .= $this->escape($parameter->getName());

                $type = $this->getParameterType($parameter);
                if ($type !== null) {
                    $label .= ' : ' . $this->escape($type);
                }

                if ($parameter->isOptional()) {
                    $label .= $this->getParameterDefaultValue($parameter);
                }
            }
            $label .= ')';

            /** @var null|ReflectionNamedType $returnType */
            $returnType = $method->getReturnType();
            if ($returnType instanceof ReflectionNamedType) {
                $type = $returnType->getName();
                if ($type !== null) {
                    $label .= ' : ' . ($returnType->allowsNull() ? '?' : '') . $this->escape($type);
                }
            }

            // align this line to the left
            $label .= '\\l';
        }

        return $label;
    }
}
