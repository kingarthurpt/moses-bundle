<?php

namespace ArturZeAlves\MosesBundle;

use ArturZeAlves\MosesBundle\Writer\DefaultWriter;

class Moses
{
    /**
     * @var Writer
     */
    private $writer;

    /**
     * Constructor
     *
     * @param DefaultWriter $writer
     */
    public function __construct(DefaultWriter $writer)
    {
        $this->writer = $writer;
    }

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
     * Gets the test file name
     *
     * @param  string $className
     *
     * @return string
     */
    public function getTestFileName($className)
    {
        return $className."Test";
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

    /**
     * Guesses the namespace to be applied to the generated test class
     *
     * @param  string $namespace
     *
     * @return string
     */
    public function guessTestNamespace($namespace)
    {
        $parts = explode("\\", $namespace);
        array_splice($parts, -1, 0, "Tests");

        return implode("\\", $parts);
    }

    /**
     * Guesses the file path of the generated test class
     *
     * @param  string $filename
     * @param  string $className
     *
     * @return string
     */
    public function guessTestFilePath($filename, $className = "")
    {
        $parts = explode("/", dirname($filename));
        array_splice($parts, -1, 0, "Tests");

        $path = implode("/", $parts);
        if (!empty($className)) {
            return $path."/".$this->getTestFileName($className).".php";
        }

        return $path;
    }

    /**
     * Generates the test class
     *
     * @param  \ReflectionClass $reflection
     * @param  string           $testNamespace
     *
     * @return string
     */
    public function generate($reflection, $testNamespace)
    {
        return $this->writer->writeClass($reflection, $testNamespace);
    }
}
