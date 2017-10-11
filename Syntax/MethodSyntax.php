<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * MethodSyntax
 */
abstract class MethodSyntax
{
    /**
     * @var \ReflectionClass
     */
    protected $reflection;

    /**
     * @var DocBlockSyntax
     */
    protected $docBlockSyntax;

    /**
     * Constructor
     *
     * @param DocBlockSyntax $docBlockSyntax
     */
    public function __construct(DocBlockSyntax $docBlockSyntax)
    {
        $this->docBlockSyntax = $docBlockSyntax;
    }

    /**
     * Sets the class reflection
     *
     * @param string $reflection
     *
     * @return self
     */
    public function setReflection($reflection)
    {
        $this->reflection = $reflection;

        return $this;
    }

    /**
     * Gets functions from the reflection class only with a given visibility
     *
     * @param int $visibility
     *
     * @return array
     */
    protected function getClassFunctions($visibility)
    {
        $functions = [];
        foreach($this->reflection->getMethods($visibility) as $method) {
            if ($method->class == $this->reflection->getName()) {
                $functions[] = $method;
            }
        }

        return $functions;
    }
}
