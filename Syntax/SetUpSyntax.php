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
     * [Constructor
     *
     * @param UseSyntax $useSyntax
     */
    public function __construct(UseSyntax $useSyntax)
    {
        $this->useSyntax = $useSyntax;
    }

    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        $classShortName = $this->reflection->getShortName();
        $className = $this->reflection->getName();

        $this->useSyntax->addUseStatement($className);
        $property = '$this->' . lcfirst($classShortName);

        $arguments = [];
        $argumentProphecies = [];
        $parameters = $this->reflection->getConstructor()->getParameters();
        foreach ($parameters as $parameter) {
            $class = $this->getClassName($parameter);
            $this->useSyntax->addUseStatement($class);
            $name = $parameter->getName();

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
}
