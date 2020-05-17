<?php
declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\Formatter\FormatterInterface;
use Bartlett\GraphUml\Formatter\HtmlFormatter;
use Bartlett\GraphUml\Formatter\RecordFormatter;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

/**
 * The concrete GraphViz generator built by composition rather than inheritance.
 * Common functions to all generators are provided by AbstractGenerator class
 */
class GraphVizGenerator extends AbstractGenerator implements GeneratorInterface
{
    /** @var GraphViz  */
    private $graphViz;

    public function __construct(GraphViz $graphViz)
    {
        $this->graphViz = $graphViz;

        /**
         * Usually, your graphviz executables should be located in your $PATH
         * environment variable and invoking a mere `dot` is sufficient. If you
         * have no access to your $PATH variable, use this method to set the path
         * to your graphviz dot executable.
         *
         * This should contain '.exe' on windows.
         * - /full/path/to/bin/dot
         * - neato
         * - dot.exe
         * - c:\path\to\bin\dot.exe
         */
        $this->setExecutable('dot');
        // (invoke dot -? for details on available formats)
        $this->setFormat('png');
    }
    
    public function getFormatter(): FormatterInterface
    {
        if ('html' === $this->options['label_format']) {
            return new HtmlFormatter($this->options);
        }
        return new RecordFormatter($this->options);
    }

    public function getName(): string
    {
        return 'graphviz';
    }

    public function getPrefix(): string
    {
        return $this->getName() . '.';
    }

    public function createScript(Graph $graph): string
    {
        // convert graph attributes to specific Graphp\GraphViz\GraphViz class
        $keys = array_keys($graph->getAttributes());

        array_walk($keys, function(&$value, $key, $prefix = 'graphviz.') {
            $value = $prefix . $value;
        });
        $attributes = array_combine($keys, array_values($graph->getAttributes()));

        $graph->setAttributes($attributes);

        return $this->graphViz->createScript($graph);
    }

    public function createImageFile(Graph $graph, string $cmdFormat = ''): string
    {
        if (empty($cmdFormat)) {
            // default command format, when none provided
            $cmdFormat = sprintf(
                '%s -T%s %s -o %s',
                self::CMD_EXECUTABLE,
                self::CMD_FORMAT,
                self::CMD_TEMP_FILE,
                self::CMD_OUTPUT_FILE
            );
        }

        return parent::createImageFile($graph, $cmdFormat);
    }
}
