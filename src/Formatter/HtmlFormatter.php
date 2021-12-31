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
use function count;
use function gettype;
use function sprintf;

/**
 * @author Laurent Laville
 */
final class HtmlFormatter extends AbstractFormatter implements FormatterInterface
{
    private const DEFAULT_ROW_FORMAT = '    <tr><td align="left">%s</td></tr>';

    public function __construct(array $options)
    {
        if (!isset($options['row_format'])) {
            $options['row_format'] = self::DEFAULT_ROW_FORMAT;
        }
        parent::__construct($options);
    }

    public function getLabelExtension(ReflectionExtension $reflection): string
    {
        $constants = $this->getLabelConstants($reflection);
        $operations = $this->getLabelFunctions($reflection->getFunctions());

        $label = '
<table cellspacing="0" border="0" cellborder="1">
    <tr><td bgcolor="#eeeeee"><b>«extension»<br/>' . $reflection->getName() . '</b></td></tr>
';
        if (!empty($constants)) {
            $label .= '    <tr><td>' . $constants . '</td></tr>' . self::EOL;
        }

        $label .= '    <tr><td>' . $operations . '</td></tr>
</table>';

        return $label;
    }

    public function getLabelClass(ReflectionClass $reflection): string
    {
        $constants = $this->getLabelConstants($reflection);
        $fields = $this->getLabelProperties($reflection);
        $operations = $this->getLabelFunctions($reflection->getMethods(), $reflection->getName());

        $label = '
<table cellspacing="0" border="0" cellborder="1">
    <tr><td bgcolor="#eeeeee"><b>' . $this->getStereotype($reflection) . '<br/>' . $this->escape($reflection->getShortName()) . '</b></td></tr>
';

        if (!empty($constants)) {
            $label .= '    <tr><td>' . $constants . '</td></tr>' . self::EOL;
        }

        $label .= '    <tr><td>' . $fields . '</td></tr>
    <tr><td>' . $operations . '</td></tr>
</table>';

        return $label;
    }

    public function getLabelConstants($reflection): string
    {
        if (!$this->options['show_constants']) {
            return '';
        }

        $parent = ($reflection instanceof ReflectionClass) ? $reflection->getParentClass() : false;
        $tbody = '';

        foreach ($reflection->getConstants() as $name => $value) {
            if ($this->options['only_self'] && $parent && $parent->getConstant($name) === $value) {
                continue;
            }

            $label = '+ «static» '
                . $this->escape($name)
                . ' : '
                . $this->escape($this->getType(gettype($value)))
                . ' = '
                . $this->getCasted($value)
                . ' {readOnly}'
            ;
            $tbody .= sprintf($this->options['row_format'], $label);
            $tbody .= self::EOL;
        }

        if (empty($tbody)) {
            return '';
        }

        $constants = '<table border="0" cellspacing="0" cellpadding="2">' . self::EOL;
        $constants .= $tbody;
        $constants .= '</table>';

        return $constants;
    }

    public function getLabelProperties(ReflectionClass $reflection): string
    {
        if (!$this->options['show_properties']) {
            return '';
        }

        $properties = $reflection->getProperties();

        if (count($properties) === 0) {
            return '';
        }

        $defaults = $reflection->getDefaultProperties();
        $tbody = '';

        foreach ($properties as $property) {
            if ($this->options['only_self'] && $property->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            if (!$this->isVisible($property)) {
                continue;
            }

            $label = $this->visibility($property);
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
            $tbody .= sprintf($this->options['row_format'], $label);
            $tbody .= self::EOL;
        }

        if (empty($tbody)) {
            return '';
        }

        $fields = '<table border="0" cellspacing="0" cellpadding="2">' . self::EOL;
        $fields .= $tbody;
        $fields .= '</table>';

        return $fields;
    }

    public function getLabelFunctions(array $functions, string $class = ''): string
    {
        if ($class && !$this->options['show_methods']) {
            return '';
        }

        $tbody = '';

        foreach ($functions as $method) {
            $label = '';
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

            $tbody .= sprintf($this->options['row_format'], $label);
            $tbody .= self::EOL;
        }

        if (empty($tbody)) {
            return '';
        }

        $operations = '<table border="0" cellspacing="0" cellpadding="2">' . self::EOL;
        $operations .= $tbody;
        $operations .= '</table>';

        return $operations;
    }
}
