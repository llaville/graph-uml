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
use UnexpectedValueException;
use function escapeshellcmd;
use function file_put_contents;
use function sprintf;
use function strtr;
use function sys_get_temp_dir;
use function system;
use function tempnam;
use function unlink;

/**
 * @author Laurent Laville
 */
abstract class AbstractGenerator
{
    protected const CMD_EXECUTABLE = '%E';
    protected const CMD_FORMAT = '%F';
    protected const CMD_OUTPUT_FILE = '%o';
    protected const CMD_TEMP_FILE = '%t';

    /**
     * List of options to personalize generator and formatters
     * @var array<string, mixed>
     */
    protected $options;

    /**
     * The name of full path to GraphViz (or other) layout
     * @var string
     */
    private $executable;

    /**
     * File output format to use
     * @var string
     */
    private $format;

    /**
     * @param array<string, mixed> $values
     */
    public function setOptions(array $values): void
    {
        $this->options = $values;
    }

    abstract public function getFormatter(): FormatterInterface;

    abstract public function getName(): string;

    public function getPrefix(): string
    {
        return '';
    }

    public function getLabelClass(ReflectionClass $reflection): string
    {
        return $this->getFormatter()->getLabelClass($reflection);
    }

    public function getLabelExtension(ReflectionExtension $reflection): string
    {
        return $this->getFormatter()->getLabelExtension($reflection);
    }

    /**
     * Change the executable to use.
     *
     * @param string $executable
     */
    public function setExecutable(string $executable): void
    {
        $this->executable = $executable;
    }

    /**
     * Change the graph image output format.
     *
     * @param string $format png, svg, eps, pdf, ...
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    abstract public function createScript(Graph $graph): string;

    /**
     * Make image file, and returns command used to generated it.
     *
     * @param Graph $graph
     * @param string $cmdFormat
     * @return string
     */
    public function createImageFile(Graph $graph, string $cmdFormat): string
    {
        $script = $this->createScript($graph);

        $tmp = tempnam(sys_get_temp_dir(), $this->getName());
        if ($tmp === false) {
            throw new UnexpectedValueException(
                sprintf('Unable to get temporary file name for %s script', $this->getName())
            );
        }

        $ret = file_put_contents($tmp, $script, LOCK_EX);
        if ($ret === false) {
            throw new UnexpectedValueException(
                sprintf('Unable to write %s script to temporary file', $this->getName())
            );
        }

        $outFile = $tmp . '.' . $this->format;
        $ret = 0;

        $cmdReplacePairs = [
            self::CMD_EXECUTABLE => $this->executable,
            self::CMD_FORMAT => $this->format,
            self::CMD_TEMP_FILE => $tmp,
            self::CMD_OUTPUT_FILE => $outFile,
        ];

        $command = strtr($cmdFormat, $cmdReplacePairs);

        system(escapeshellcmd($command), $ret);
        if ($ret !== 0) {
            throw new UnexpectedValueException(
                'Unable to invoke "' . $this->executable . '" to create image file (code ' . $ret . ')'
            );
        }
        unlink($tmp);

        return $command;
    }
}
