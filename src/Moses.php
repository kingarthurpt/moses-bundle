<?php

namespace ArturZeAlves;

use ArturZeAlves\Writer\DefaultWriter;

class Moses
{
    /**
     * @var Writer
     */
    private $writer;

    /**
     * Constructor.
     *
     * @param DefaultWriter $writer
     */
    public function __construct(DefaultWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Returns the DefaultWriter.
     *
     * @return DefaultWriter
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Generates the test class.
     *
     * @param \ReflectionClass $reflection
     * @param string           $testNamespace
     *
     * @return string
     */
    public function prophesize($reflection, $testNamespace)
    {
        return $this->writer->writeClass($reflection, $testNamespace);
    }
}
