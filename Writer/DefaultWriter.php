<?php

namespace ArturZeAlves\MosesBundle\Writer;

class DefaultWriter
{
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
        $functions = $this->convertPublicFunctions($reflection);
        $functions .= $this->convertPrivateFunctions($reflection);
        $str = <<<EOF
<?php

namespace ${testNamespace};

class ${className}Test extends \PHPUnit_Framework_TestCase
{
${functions}
}
EOF;

        return $str;
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

        $result = $this->writeSetUp($reflection);
        foreach ($methods as $method) {
            // $string = $this->writeDocBlock();
            // if ($method->isConstructor()) {
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
        // $longClassName = $reflection->getName();
        // $this->addUseStatement($longClassName);
        $objectName = lcfirst($className);

        // $parameters = $method->getParameters();
        // foreach ($parameters as $parameter) {
        //     $class = $parameter->getClass();
        //     $name = $parameter->getName();
        // }

        return <<<EOF
    public function setUp()
    {
        $${objectName} = new {$className}();
    }\n\n
EOF;
    }

    /**
     * Writes a public function
     *
     * @param  \ReflectionClass $reflection
     * @param  \ReflectionMethod $method
     *
     * @return string
     */
    public function writePublicFunction($reflection, $method)
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
    public function writePrivateFunction($reflection, $method)
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
    public function writeDocBlock()
    {
        $str = <<<EOF
    /**
     *
     */\n
EOF;

        return $str;
    }

    /**
     * Gets functions from the reflection class only with a given visibility
     * @param  \ReflectionClass $reflection
     * @param  int $visibility
     * @return array
     */
    private function getClassFunctions($reflection, $visibility)
    {
        $functions = [];
        foreach($reflection->getMethods($visibility) as $method) {
            if ($method->class == $reflection->getName()) {
                $functions[] = $method;
            }
        }

        return $functions;
    }
}
