<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

use Bartlett\GraphUml\ClassDiagramBuilder;
use Bartlett\GraphUml\Generator\GraphVizGenerator;

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;

$example = $_SERVER['argv'][1] ?? null;
$folder = $_SERVER['argv'][2] ?? sys_get_temp_dir();
$format = $_SERVER['argv'][3] ?? 'svg';
$writeGraphStatement = $_SERVER['argv'][4] ?? false;

$resources = [
    __DIR__ . '/bootstrap.php',     // autoloader or any other resource to include before run this script
    __DIR__ . '/datasource.php',    // list of files or classes to parse
    __DIR__ . '/options.php',       // all options to customize the Graph
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
if (!isset($datasource)) {
    $datasource = fn() => [];
}

$generator = new GraphVizGenerator(new GraphViz(), 'dot', $format);
$graph = new Graph();
$builder = new ClassDiagramBuilder($generator, $graph, $options ?? []);

foreach ($datasource() as $i => $extension) {
    $attributes = ($i === 0) ? ['fillcolor' => 'burlywood3'] : [];
    $builder->createVertexExtension($extension, $attributes);
}

if ($writeGraphStatement) {
    // writes graphviz statements to file
    $output = rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $example . '.html.gv';
    file_put_contents($output, $generator->createScript($graph));
}

$output = rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $example . '.graphviz.' . $format;
$cmdFormat = '%E -T%F %t -o ' . $output;
$target = $generator->createImageFile($graph, $cmdFormat);
echo (empty($target) ? 'no' : $target) . ' file generated' . PHP_EOL;
