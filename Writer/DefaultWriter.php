<?php

namespace ArturZeAlves\MosesBundle\Writer;

use ArturZeAlves\MosesBundle\Syntax\ClassSyntax;
use ArturZeAlves\MosesBundle\Syntax\DocBlockSyntax;
use Symfony\Component\Filesystem\Filesystem;

class DefaultWriter
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var ClassSyntax
     */
    protected $classSyntax;

    /**
     * Constructor
     *
     * @param ClassSyntax $classSyntax
     *
     */
    public function __construct(Filesystem $filesystem, ClassSyntax $classSyntax)
    {
        $this->filesystem = $filesystem;
        $this->classSyntax = $classSyntax;
    }

    /**
     * Gets the Filesystem
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Writes a class
     *
     * @param \ReflectionClass $reflection
     * @param string $testNamespace
     *
     * @return string
     */
    public function writeClass(\ReflectionClass $reflection, $testNamespace)
    {
        return $this->classSyntax
            ->setReflection($reflection)
            ->setNamespace($testNamespace)
            ->getText();
    }
}
