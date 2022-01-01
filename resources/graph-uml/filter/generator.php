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

use Bartlett\GraphUml\Filter\NamespaceFilterInterface;

$namespaceFilter = new class() implements NamespaceFilterInterface
{
    private $shortClass;

    public function filter(string $fqcn): ?string
    {
        $nameParts = explode('\\', $fqcn);
        $this->shortClass = array_pop($nameParts);  // removes short name part of Fully Qualified Class Name

        if (
            count($nameParts) === 3
            && $nameParts[0] == 'Bartlett'
            && $nameParts[1] == 'GraphUml'
            && $nameParts[2] == 'Generator'
        ) {
            return implode('\\', $nameParts);
        }
        return null;
    }

    public function getShortClass(): ?string
    {
        return $this->shortClass;
    }
};
