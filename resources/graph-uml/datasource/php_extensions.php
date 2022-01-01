<?php declare(strict_types=1);
/**
 * This file is part of the Graph-UML package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @since Release 1.2.0
 * @author Laurent Laville
 */

function dataSource(): Generator
{
    $extensions = [
        'lzf',
        'yaml',
    ];
    foreach ($extensions as $extension) {
        yield $extension;
    }
}
