<?php

namespace ArturZeAlves\Syntax;

use ArturZeAlves\OOP\ClassProperty;

/**
 * DocBlockSyntax.
 */
class DocBlockSyntax implements SyntaxInterface
{
    /**
     * {@inheritdoc}
     */
    public function getText()
    {
        $str = <<<EOF
    /**
     *
     */\n
EOF;

        return $str;
    }

    public function getClassPropertyText(ClassProperty $property): string
    {
        $str = <<<EOF
    /**
     * @var {$property->getType()}
     */\n
EOF;

        return $str;
    }
}
