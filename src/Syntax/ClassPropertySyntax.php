<?php

namespace ArturZeAlves\Syntax;

use ArturZeAlves\OOP\ClassProperty;

/**
 * ClassPropertySyntax.
 */
class ClassPropertySyntax implements SyntaxInterface
{
    /**
     * @var DocBlockSyntax
     */
    protected $docBlockSyntax;

    /**
     * @var array
     */
    private $properties = [];

    /**
     * Constructor.
     *
     * @param DocBlockSyntax $docBlockSyntax
     */
    public function __construct(DocBlockSyntax $docBlockSyntax)
    {
        $this->docBlockSyntax = $docBlockSyntax;
    }

    /**
     * {@inheritdoc}
     */
    public function getText()
    {
        $str = '';
        foreach ($this->properties as $property) {
            $str .= $this->docBlockSyntax->getClassPropertyText($property);
            $str .= sprintf("    %s $%s;\n\n", $property->getVisibility(), $property->getName());
        }

        return $str;
    }

    /**
     * Adds a property.
     *
     * @param string $shortClassName
     * @param string $property
     * @param string $visibility
     */
    public function addProperty($shortClassName, $property, $visibility)
    {
        if (!$this->isValidVisibility($visibility)) {
            return;
        }

        $this->properties[] = new ClassProperty($visibility, $property, $shortClassName);
    }

    /**
     * Checks if a visibility is valid.
     *
     * @return bool TRUE if it is, FALSE otherwise
     *
     * @param mixed $visibility
     */
    private function isValidVisibility($visibility)
    {
        switch ($visibility) {
            case ClassProperty::VISIBILITY_PUBLIC:
            case ClassProperty::VISIBILITY_PROTECTED:
            case ClassProperty::VISIBILITY_PRIVATE:
                return true;
            default:
                return false;
        }
    }
}
