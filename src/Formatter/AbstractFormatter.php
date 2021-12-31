<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\GraphUml\Formatter;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;
use Reflector;
use function count;
use function get_class;
use function htmlentities;
use function in_array;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_object;
use function is_string;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function str_replace;
use function strtolower;
use function substr;
use function trim;
use const PHP_EOL;
use const PREG_SET_ORDER;

/**
 * @author Laurent Laville
 */
abstract class AbstractFormatter
{
    protected const EOL = PHP_EOL;

    /**
     * @var array<string, mixed>
     */
    protected $options;

    /**
     * @param array<string, mixed> $options
     */
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
        return $reflection->isPublic()                                          // @phpstan-ignore-line
            || ($reflection->isProtected() && $this->options['show_protected']) // @phpstan-ignore-line
            || ($reflection->isPrivate() && $this->options['show_private']);    // @phpstan-ignore-line
    }

    /**
     * @param ReflectionFunctionAbstract|ReflectionProperty $ref
     */
    protected function getDocBlock($ref): ?string
    {
        $doc = $ref->getDocComment();
        if ($doc !== false) {
            return trim(preg_replace('/(^(?:\h*\*)\h*|\h+$)/m', '', substr($doc, 3, -2)));
        }

        return null;
    }

    protected function getDocBlockVar(ReflectionProperty $ref): ?string
    {
        return $this->getType($this->getDocBlockSingle($ref, 'var'));
    }

    protected function getParameterType(ReflectionParameter $parameter): ?string
    {
        $class = null;
        try {
            // get class hint for parameter
            /** @var null|ReflectionType $class */
            $class = $parameter->getType();
            // will fail if specified class does not exist
        } catch (Exception $ignore) {
            return '«invalidClass»';
        }

        if ($class instanceof ReflectionNamedType) {
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

    protected function getParameterDefaultValue(ReflectionParameter $parameter): string
    {
        try {
            if ($parameter->isDefaultValueConstant()) {
                $defaultValue = $parameter->getDefaultValueConstantName();
                if (null === $defaultValue) {
                    return ' = «unknown»';
                }
                return ' = ' . $this->getCasted(
                    str_replace(['self::', 'static::'], '', $defaultValue),
                    ''
                );
            }

            if ($parameter->isDefaultValueAvailable()) {
                $defaultValue = $parameter->getDefaultValue();
                if (null === $defaultValue) {
                    return ' = «unknown»';
                }
                return ' = ' . $this->getCasted($defaultValue, "'");
            }

            return '';
        } catch (ReflectionException $e) {
            // Cannot determine default value for internal functions
            return ' = «unknown»';
        }
    }

    /**
     * @param ReflectionFunctionAbstract|ReflectionProperty $ref
     * @param string $what
     * @return string[]
     */
    protected function getDocBlockMulti($ref, string $what): array
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

    protected function getDocBlockSingle(ReflectionProperty $ref, string $what): ?string
    {
        $multi = $this->getDocBlockMulti($ref, $what);
        if (count($multi) !== 1) {
            return null;
        }

        return $multi[0];
    }

    protected function getType(?string $ret): ?string
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

    /**
     * @param mixed $value
     * @param string $quoted
     * @return string
     */
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
        if ($reflection->isPublic()) {          // @phpstan-ignore-line
            return '+';
        } elseif ($reflection->isProtected()) { // @phpstan-ignore-line
            return '#';
        } elseif ($reflection->isPrivate()) {   // @phpstan-ignore-line
            // U+2013 EN DASH "–"
            return "\342\200\223";
        }
        return '?';
    }

    protected function escape(string $id): string
    {
        return preg_replace(
            '/([^\\w])/u',
            '\\\\$1',
            str_replace(["\r", "\n", "\t"], ['\\r', '\\n', '\\t'], $id)
        );
    }
}
