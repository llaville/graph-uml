<?php
declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\Formatter\FormatterInterface;

use Graphp\Graph\Exception\UnexpectedValueException;
use Graphp\Graph\Graph;

use ReflectionClass;
use ReflectionExtension;

abstract class AbstractGenerator
{
    protected const CMD_EXECUTABLE = '%E';
    protected const CMD_FORMAT = '%F';
    protected const CMD_OUTPUT_FILE = '%o';
    protected const CMD_TEMP_FILE = '%t';

    /**
     * List of options to personalize generator and formatters
     *
     * @var array
     */
    protected $options;

    /**
     * The name of full path to GraphViz (or other) layout
     *
     * @var string
     */
    private $executable;

    /**
     * File output format to use
     *
     * @var string
     */
    private $format;

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
                'Unable to invoke "' . $this->executable .'" to create image file (code ' . $ret . ')'
            );
        }
        unlink($tmp);

        return $outFile;
    }
}
