<?php
declare(strict_types=1);

namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\Formatter\HtmlFormatter;
use Bartlett\GraphUml\Formatter\RecordFormatter;
use Bartlett\GraphUml\FormatterInterface;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

/**
 * The concrete GraphViz generator
 */
class GraphVizGenerator extends GraphViz implements GeneratorInterface
{
    use AbstractGeneratorTrait;

    public function getFormatter(): FormatterInterface
    {
        if ('html' === $this->options['label-format']) {
            return new HtmlFormatter($this->options);
        }
        return new RecordFormatter($this->options);
    }

    public function getName(): string
    {
        return 'graphviz';
    }

    public function render(Graph $graph): string
    {
        return parent::createScript($graph);
    }
}
