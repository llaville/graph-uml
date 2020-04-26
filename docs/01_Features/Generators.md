GraPHP UML used at least two components :

- the mathematical graph/network [GraPHP](https://github.com/graphp/graph) library to draw UML diagrams.
- any generator that implement the following contract. 
GraPHP UML uses [GraphVizGenerator](https://github.com/llaville/graph-uml/blob/master/src/Generator/GraphVizGenerator.php) as default, but allow others that may be registered later at runtime.

### Contract

Each generator used to build graph statements should implement following interface:

```php
namespace Bartlett\GraphUml\Generator;

use Bartlett\GraphUml\FormatterInterface;

use Graphp\Graph\Graph;

interface GeneratorInterface
{
    public function getFormatter(): FormatterInterface;

    public function getName(): string;

    public function render(Graph $graph): string;
}
```

* `getFormatter()` is in charge to retrieve instance of a formatter that will produce vertex labels.

* `getName()` identifies the generator with a unique name.

* `render()` is in charge to build graph statements depending of generator used.
