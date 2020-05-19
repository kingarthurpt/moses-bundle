<?php

namespace ArturZeAlves\OOP;

/**
 * ClassProperty.
 */
class ClassProperty
{
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PRIVATE = 'private';

    private $visibility;
    private $type;
    private $name;
    private $docBlock;

    /**
     * @param string      $visibility
     * @param string      $name
     * @param string|null $type
     * @param string|null $docBlock
     */
    public function __construct($visibility, $name, $type = null, $docBlock = null)
    {
        $this->visibility = $visibility;
        $this->name = $name;
        $this->type = $type;
        $this->docBlock = $docBlock;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDocBlock()
    {
        return $this->docBlock;
    }
}
