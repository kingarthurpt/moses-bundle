<?php

namespace ArturZeAlves\MosesBundle\Writer;

class DefaultWriter
{
    /**
     * @var array
     */
    private $uses = [];

    /**
     * Writes a class
     *
     * @param  \ReflectionClass $reflection
     * @param  string $testNamespace
     *
     * @return string
     */
    public function writeClass($reflection, $testNamespace)
    {
        $namespace = $reflection->getNamespaceName();
        $className = $reflection->getShortName();
        $functions = $this->writeSetUp($reflection);
        $functions .= $this->convertPublicFunctions($reflection);
        $functions .= $this->convertPrivateFunctions($reflection);
        $useStatements = $this->writeUseStatements();

        $str = <<<EOF
<?php

namespace ${testNamespace};

${useStatements}
class ${className}Test extends \PHPUnit_Framework_TestCase
{
${functions}
}
EOF;

        return $str;
    }

    /**
     * Writes the setUp function to prophesize the class constructor
     *
     * @param  \ReflectionClass $reflection
     * @param  \ReflectionMethod $method
     *
     * @return string
     */
    public function writeSetUp($reflection)
    {
        $className = $reflection->getShortName();
        $longClassName = $reflection->getName();
        $this->addUseStatement($longClassName);
        $property = '$this->' . lcfirst($className);

        $parameters = $reflection->getConstructor()->getParameters();
        $arguments = [];
        $argumentProphecies = [];
        foreach ($parameters as $parameter) {
            $class = $this->getClassName($parameter);
            $this->addUseStatement($class);
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

        $property = new $className($arguments);
    }\n\n
EOF;
    }

    /**
     * Writes all public functions
     *
     * @param  \ReflectionClass $reflection
     *
     * @return string
     */
    protected function convertPublicFunctions($reflection)
    {
        $methods = $this->getClassFunctions($reflection, \ReflectionMethod::IS_PUBLIC);

        $result = "";
        foreach ($methods as $method) {
            // $string = $this->writeDocBlock();
            if ($method->isConstructor()) {
                continue;
            }
            $result .= $this->writePublicFunction($reflection, $method);
        }

        return $result;
    }

    /**
     * Writes all private functions
     *
     * @param  \ReflectionClass $reflection
     *
     * @return string
     */
    protected function convertPrivateFunctions($reflection)
    {
        $methods = $this->getClassFunctions($reflection, \ReflectionMethod::IS_PRIVATE);

        $result = "";
        foreach ($methods as $method) {
            $string = $this->writeDocBlock();
            $string .= $this->writePrivateFunction($reflection, $method);
            $result .= $string;
        }

        return $result;
    }

    /**
     * Writes a public function
     *
     * @param  \ReflectionClass $reflection
     * @param  \ReflectionMethod $method
     *
     * @return string
     */
    protected function writePublicFunction($reflection, $method)
    {
        $className = $reflection->getName();
        $objectName = lcfirst($className);
        $method = ucwords($method->getName());

        // var_dump($method->getParameters());
        // ReflectionParameter#5

        return <<<EOF
    public function test${method}()
    {

    }\n\n
EOF;
    }

    /**
     * Writes a private function
     *
     * @param  \ReflectionClass $reflection
     * @param  \ReflectionMethod $method
     *
     * @return string
     */
    protected function writePrivateFunction($reflection, $method)
    {
        $className = $reflection->getName();
        $objectName = lcfirst($className);
        $method = ucwords($method->getName());

        // var_dump($method->getParameters());
        // ReflectionParameter#5

        return <<<EOF
    private function prophesize${method}()
    {

    }\n\n
EOF;
    }

    /**
     * Writes the docblock
     *
     * @param  \ReflectionClass $reflection
     * @param  \ReflectionMethod $method
     *
     * @return string
     */
    protected function writeDocBlock()
    {
        $str = <<<EOF
    /**
     *
     */\n
EOF;

        return $str;
    }

    /**
     * Writes all use statements
     *
     * @return string
     */
    protected function writeUseStatements()
    {
        sort($this->uses);

        $str = "";
        foreach ($this->uses as $use) {
            $str .= $use . "\n";
        }

        return $str;
    }

    /**
     * Adds a use statement
     *
     * @param string class
     */
    protected function addUseStatement($class)
    {
        $this->uses[] = <<<EOF
use ${class};
EOF;
    }

    /**
     * Gets functions from the reflection class only with a given visibility
     *
     * @param  \ReflectionClass $reflection
     * @param  int $visibility
     *
     * @return array
     */
    protected function getClassFunctions($reflection, $visibility)
    {
        $functions = [];
        foreach($reflection->getMethods($visibility) as $method) {
            if ($method->class == $reflection->getName()) {
                $functions[] = $method;
            }
        }

        return $functions;
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
