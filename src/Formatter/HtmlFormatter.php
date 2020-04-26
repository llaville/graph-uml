<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Formatter;

use Exception;
use ReflectionClass;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionParameter;

class HtmlFormatter extends AbstractFormatter implements FormatterInterface
{
    private const DEFAULT_ROW_FORMAT = '<tr><td align="left">%s</td></tr>';

    public function __construct(array $options)
    {
        if (!isset($options['row-format'])) {
            $options['row-format'] = self::DEFAULT_ROW_FORMAT;
        }
        parent::__construct($options);
    }

    public function getLabelExtension(ReflectionExtension $reflection): string
    {
        $constants = $this->getLabelConstants($reflection);
        $operations = $this->getLabelFunctions($reflection->getFunctions());

        return '
<table cellspacing="0" border="0" cellborder="1">
    <tr><td bgcolor="#eeeeee"><b>«extension»<br/>' . $reflection->getName() . '</b></td></tr>
    ' . $constants . '
    <tr><td>' . $operations . '</td></tr>
</table>';
    }

    public function getLabelClass(ReflectionClass $reflection): string
    {
        $constants = $this->getLabelConstants($reflection);
        $fields = $this->getLabelProperties($reflection);
        $operations = $this->getLabelFunctions($reflection->getMethods(), $reflection->getName());

        return '
<table cellspacing="0" border="0" cellborder="1">
    <tr><td bgcolor="#eeeeee"><b>' . $this->getStereotype($reflection) . '<br/>' . $this->escape($reflection->getShortName()) . '</b></td></tr>
    ' . $constants . '
    <tr><td>' . $fields . '</td></tr>
    <tr><td>' . $operations . '</td></tr>
</table>';
    }

    public function getLabelConstants($reflection): string
    {
        if (!$this->options['show-constants']) {
            return '';
        }

        $label = '';
        $parent = ($reflection instanceof ReflectionClass) ? $reflection->getParentClass() : false;

        foreach ($reflection->getConstants() as $name => $value) {
            if ($this->options['only-self'] && $parent && $parent->getConstant($name) === $value) {
                continue;
            }

            $label .= sprintf(
                $this->options['row-format'],
                '+ «static» '
                . $this->escape($name)
                . ' : '
                . $this->escape($this->getType(gettype($value)))
                . ' = '
                . $this->getCasted($value)
                . ' {readOnly}'
            );
            $label .= self::EOL;
        }

        return $label;
    }

    public function getLabelProperties(ReflectionClass $reflection): string
    {
        $properties = $reflection->getProperties();

        if (count($properties) === 0) {
            return '';
        }

        $defaults = $reflection->getDefaultProperties();
        $tbody = '';

        foreach ($properties as $property) {
            if ($this->options['only-self'] && $property->getDeclaringClass()->getName() !== $reflection->getName()) {
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
            if ($type !== NULL) {
                $label .= ' : ' . $this->escape($type);
            }

            // only show non-NULL values
            if (isset($defaults[$property->getName()])) {
                $label .= ' = ' . $this->getCasted($defaults[$property->getName()]);
            }
            $tbody .= sprintf($this->options['row-format'], $label);
            $tbody .= self::EOL;
        }

        if (empty($tbody)) {
            return '';
        }

        $fields = '<table border="0" cellspacing="0" cellpadding="2">' . PHP_EOL;
        $fields .= $tbody;
        $fields .= '</table>';

        return $fields;
    }

    public function getLabelFunctions(array $functions, string $class = null): string
    {
        $tbody = '';

        foreach ($functions as $method) {
            $label = '';
            if ($method instanceof ReflectionMethod) {
                // method not defined in this class (inherited from parent), so skip
                if ($this->options['only-self'] && $method->getDeclaringClass()->getName() !== $class) {
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
                /** @var $parameter ReflectionParameter */
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
                    try {
                        $label .= ' = ' . $this->getCasted(
                            str_replace(['self::', 'static::'], '', $parameter->getDefaultValueConstantName()),
                            ''
                        );
                    } catch (Exception $ignore) {
                        $label .= ' = «unknown»';
                    }
                }
            }
            $label .= ')';

            $returnType = $method->getReturnType();
            if ($returnType) {
                $type = (string) $returnType;
                if ($type !== null) {
                    $label .= ' : ' . ($returnType->allowsNull() ? '?' : '') . $this->escape($type);
                }
            }

            $tbody .= sprintf($this->options['row-format'], $label);
            $tbody .= self::EOL;
        }

        if (empty($tbody)) {
            return '';
        }

        $operations = '<table border="0" cellspacing="0" cellpadding="2">' . PHP_EOL;
        $operations .= $tbody;
        $operations .= '</table>';

        return $operations;
    }
}
