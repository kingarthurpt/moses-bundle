<?php

namespace ArturZeAlves\MosesBundle\Parser;

class PHPFileParser
{
    /**
     * Gets the class name from a given PHP file
     *
     * @param  string $filename
     *
     * @return string
     */
    public function getClassName($filename)
    {
        return str_replace(".php", "", basename($filename));
    }

    /**
     * Gets the namespace from a given PHP file
     *
     * @param  string $filename
     *
     * @return string
     */
    public function getNamespace($filename)
    {
        $src = file_get_contents($filename);
        if (preg_match('#^namespace\s+(.+?);$#sm', $src, $match)) {
            return $match[1];
        }

        return null;
    }
}
