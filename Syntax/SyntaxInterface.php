<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * SyntaxInterface
 */
interface SyntaxInterface
{
    /**
     * Gets the output text of a specific syntax
     *
     * @return string
     */
    public function getText();
}
