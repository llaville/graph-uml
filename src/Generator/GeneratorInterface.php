<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\Formatter\FormatterInterface;

use Graphp\Graph\Graph;

use ReflectionClass;
use ReflectionExtension;

/**
 * @author Laurent Laville
 */
interface GeneratorInterface
{
    /**
     * @param array<string, mixed> $values
     */
    public function setOptions(array $values): void;

    public function getFormatter(): FormatterInterface;

    public function getName(): string;

    public function getPrefix(): string;

    public function getLabelClass(ReflectionClass $reflection): string;

    public function getLabelExtension(ReflectionExtension $reflection): string;

    public function setExecutable(string $executable): void;

    public function setFormat(string $format): void;

    public function createScript(Graph $graph): string;

    public function createImageFile(Graph $graph, string $cmdFormat): string;
}
