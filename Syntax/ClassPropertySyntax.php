<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * ClassPropertySyntax
 */
class ClassPropertySyntax implements SyntaxInterface
{
    const VISIBILITY_PUBLIC = "public";
    const VISIBILITY_PROTECTED = "protected";
    const VISIBILITY_PRIVATE = "private";

    /**
     * @var DocBlockSyntax
     */
    protected $docBlockSyntax;

    /**
     * @var array
     */
    private $properties = [];

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
     * @{inheritDoc}
     */
    public function getText()
    {
        $str = "";
        foreach ($this->properties as $property) {
            $str .= $this->docBlockSyntax->getText();
            $str .= "    ". $property . "\n\n";
        }

        return $str;
    }

    /**
     * Adds a property
     *
     * @param string $property
     * @param string $visibility
     */
    public function addProperty($property, $visibility)
    {
        if (!$this->isValidVisibility($visibility)) {
            return;
        }

        $this->properties[] = <<<EOF
{$visibility} {$property};
EOF;
    }

    /**
     * Checks if a visibility is valid
     *
     * @return bool TRUE if it is, FALSE otherwise
     */
    private function isValidVisibility($visibility)
    {
        switch($visibility) {
            case self::VISIBILITY_PUBLIC:
            case self::VISIBILITY_PROTECTED:
            case self::VISIBILITY_PRIVATE:
                return true;
            default:
                return false;
        }
    }
}
