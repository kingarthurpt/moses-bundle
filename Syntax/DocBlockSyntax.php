<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * DocBlockSyntax
 */
class DocBlockSyntax implements SyntaxInterface
{
    /**
     * @{inheritDoc}
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
}
