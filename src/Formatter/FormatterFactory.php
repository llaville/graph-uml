<?php

declare(strict_types=1);

namespace Bartlett\GraphUml\Formatter;

use Bartlett\GraphUml\FormatterInterface;

use DomainException;

class FormatterFactory extends AbstractFormatterFactory
{
    public function getFormatter(): FormatterInterface
    {
       switch ($this->formatter) {
           case 'html':
               return new HtmlFormatter($this->options);
           case 'record':
               return new RecordFormatter($this->options);
       }
       throw new DomainException(
           sprintf('Formatter "%s" is unknown', $this->formatter)
       );
    }
}
