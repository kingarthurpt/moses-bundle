<?php

namespace ArturZeAlves\Prediction;

class Prediction
{
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
}
