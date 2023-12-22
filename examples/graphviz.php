<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;
use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$example = $_SERVER['argv'][1] ?? null;
$format = $_SERVER['argv'][2] ?? 'svg';
$printGraphStatement = $_SERVER['argv'][3] ?? false;

$baseDir = __DIR__ . DIRECTORY_SEPARATOR . $example . DIRECTORY_SEPARATOR;
$available = is_dir($baseDir) && file_exists($baseDir);

if (empty($example) || !$available) {
    throw new RuntimeException(sprintf('Example "%s" does not exists.', $example));
}

$resources = [
    $baseDir . 'bootstrap.php',     // autoloader or any other resource to include before run this script
    $baseDir . 'datasource.php',    // list of files or classes to parse
    $baseDir . 'options.php',       // all options to customize the Graph
];

$isAutoloadFound = false;

foreach ($resources as $resource) {
    if (file_exists($resource)) {
        $variable = basename($resource, '.php');
        $$variable = require $resource;
    }
    if (isset($bootstrap) && !$isAutoloadFound) {
        $isAutoloadFound = true;
        $bootstrap();
    }
}

$generator = new GraphVizGenerator(new GraphViz());
$graph = new Graph();
$builder = new ClassDiagramBuilder($generator, $graph, $options ?? []);

foreach ($datasource() as $class) {
    $builder->createVertexClass($class);
}

// show UML diagram statements
if ($printGraphStatement) {
    $script = $generator->createScript($graph);
    echo $script;
}

// default format is PNG, change it to SVG
$generator->setFormat($format);

$target = $generator->createImageFile($graph);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
