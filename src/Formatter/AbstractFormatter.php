<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Formatter;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use Reflector;

abstract class AbstractFormatter
{
    protected const EOL = PHP_EOL;

    protected $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function getFormat(): string
    {
        return $this->options['label_format'];
    }

    protected function getStereotype(ReflectionClass $reflection): string
    {
        if ($reflection->isInterface()) {
            $label = '«interface»';
        } elseif ($reflection->isAbstract()) {
            $label = '«abstract»';
        } else {
            $label = '';
        }
        return $label;
    }

    protected function isVisible(Reflector $reflection): bool
    {
        return $reflection->isPublic()
            || ($reflection->isProtected() && $this->options['show_protected'])
            || ($reflection->isPrivate() && $this->options['show_private']);
    }

    protected function getDocBlock($ref)
    {
        $doc = $ref->getDocComment();
        if ($doc !== false) {
            return trim(preg_replace('/(^(?:\h*\*)\h*|\h+$)/m', '', substr($doc, 3, -2)));
        }

        return null;
    }

    protected function getDocBlockVar($ref)
    {
        return $this->getType($this->getDocBlockSingle($ref, 'var'));
    }

    protected function getParameterType(ReflectionParameter $parameter)
    {
        $class = null;
        try {
            // get class hint for parameter
            $class = $parameter->getClass();
            // will fail if specified class does not exist
        } catch (Exception $ignore) {
            return '«invalidClass»';
        }

        if ($class !== null) {
            return $class->getName();
        }

        $pos = $parameter->getPosition();
        $refFn = $parameter->getDeclaringFunction();
        $params = $this->getDocBlockMulti($refFn, 'param');
        if (count($params) === $refFn->getNumberOfParameters()) {
            return $this->getType($params[$pos]);
        }

        return null;
    }

    protected function getDocBlockMulti($ref, $what): array
    {
        $doc = $this->getDocBlock($ref);
        if ($doc === null) {
            return [];
        }
        preg_match_all('/^@' . $what . ' ([^\s]+)/m', $doc, $matches, PREG_SET_ORDER);
        $ret = [];
        foreach ($matches as $match) {
            $ret[] = trim($match[1]);
        }

        return $ret;
    }

    protected function getDocBlockSingle($ref, $what)
    {
        $multi = $this->getDocBlockMulti($ref, $what);
        if (count($multi) !== 1) {
            return null;
        }

        return $multi[0];
    }

    protected function getType($ret): ?string
    {
        if ($ret === null) {
            return $ret;
        }
        if (preg_match('/^array\[(\w+)\]$/i', $ret, $match)) {
            return $this->getType($match[1]) . '[]';
        }
        if (!preg_match('/^\w+$/', $ret)) {
            return 'mixed';
        }
        $low = strtolower($ret);
        if ($low === 'integer') {
            $ret = 'int';
        } elseif ($low === 'double') {
            $ret = 'float';
        } elseif ($low === 'boolean') {
            return 'bool';
        } elseif (in_array($low, ['int', 'float', 'bool', 'string', 'null', 'resource', 'array', 'void', 'mixed'])) {
            return $low;
        }

        return $ret;
    }

    protected function getCasted($value, string $quoted = '"'): string
    {
        if ($value === null) {
            return 'NULL';
        } elseif (is_string($value)) {
            return $quoted . htmlentities($this->escape($value)) . $quoted;
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_int($value) || is_float($value)) {
            return (string) $value;
        } elseif (is_array($value)) {
            if ($value === []) {
                return '[]';
            } else {
                return '[…]';
            }
        } elseif (is_object($value)) {
            return get_class($value) . '\\{…\\}';
        }
        return '…';
    }

    protected function visibility(Reflector $reflection): string
    {
        if ($reflection->isPublic()) {
            return '+';
        } elseif ($reflection->isProtected()) {
            return '#';
        } elseif ($reflection->isPrivate()) {
            // U+2013 EN DASH "–"
            return "\342\200\223";
        }
        return '?';
    }

    protected function escape($id)
    {
        return preg_replace(
            '/([^\\w])/u',
            '\\\\$1',
            str_replace(["\r", "\n", "\t"], ['\\r', '\\n', '\\t'], $id)
        );
    }
}
