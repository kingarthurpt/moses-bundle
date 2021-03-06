<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * SetUpSyntax
 */
class SetUpSyntax extends MethodSyntax implements SyntaxInterface
{
    /**
     * @var UseSyntax
     */
    protected $useSyntax;

    /**
     * @var ClassPropertySyntax
     */
    protected $classPropertySyntax;

    /**
     * Constructor
     *
     * @param UseSyntax           $useSyntax
     * @param ClassPropertySyntax $classPropertySyntax
     */
    public function __construct(
        UseSyntax $useSyntax,
        ClassPropertySyntax $classPropertySyntax
    ) {
        $this->useSyntax = $useSyntax;
        $this->classPropertySyntax = $classPropertySyntax;
    }

    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        if (null === $this->reflection->getConstructor()) {
            return "";
        }
        $parameters = $this->reflection->getConstructor()->getParameters();

        $classShortName = $this->reflection->getShortName();
        $propertyName = $this->getPropertyName($classShortName);
        $this->classPropertySyntax->addProperty($propertyName, ClassPropertySyntax::VISIBILITY_PRIVATE);

        $className = $this->reflection->getName();
        $this->useSyntax->addUseStatement($className);
        $property = '$this->' . $propertyName;

        $arguments = [];
        $argumentProphecies = [];
        foreach ($parameters as $parameter) {
            $class = $this->getClassName($parameter);
            $this->useSyntax->addUseStatement($class);
            $name = $parameter->getName();
            $this->classPropertySyntax->addProperty($name, ClassPropertySyntax::VISIBILITY_PRIVATE);

            $shortClassName = $this->getShortClassName($class);
            $arguments[] = sprintf('$this->%s->reveal()', $name);
            $argumentProphecies[] = sprintf('$this->%s = $this->prophesize(%s::class);', $name, $shortClassName);
        }
        $arguments = implode(", ", $arguments);
        $argumentProphecies = implode("\n        ", $argumentProphecies);

        return <<<EOF
    public function setUp()
    {
        $argumentProphecies

        $property = new {$classShortName}($arguments);
    }\n\n
EOF;
    }

    /**
     * Gets the class name from a ReflectionParameter
     *
     * @param  \ReflectionParameter $param
     *
     * @return string
     */
    protected function getClassName(\ReflectionParameter $param)
    {
        $str = explode(" ", $param->__toString());

        return $str[4];
    }

    /**
     * Gets the short class name from a string representing a long class name
     *
     * @param  string $class
     *
     * @return string
     */
    protected function getShortClassName($class)
    {
        $str = explode("\\", $class);

        return $str[count($str) - 1];
    }

    /**
     * Gets a shorter property name for a given short class name in camel case.
     * This shorter name is the last word in uppercase of the given name.
     *
     * @param string $classShortName
     *
     * @return string
     */
    protected function getPropertyName($classShortName)
    {
        $parts = preg_split("/(?=[A-Z])/", lcfirst($classShortName));

        return strtolower(end($parts));
    }
}
