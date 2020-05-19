<?php

namespace ArturZeAlves\Syntax;

/**
 * UseSyntax
 */
class UseSyntax implements SyntaxInterface
{
    /**
     * @var array
     */
    private $uses = [];

    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        sort($this->uses);

        $str = "";
        foreach ($this->uses as $use) {
            $str .= $use . "\n";
        }

        return $str;
    }

    /**
     * Adds a use statement
     *
     * @param string class
     */
    public function addUseStatement($class)
    {
        // Ignores non type hinted arguments
        if ($class[0] === "$") {
            return;
        }

        $this->uses[] = <<<EOF
use ${class};
EOF;
    }
}
