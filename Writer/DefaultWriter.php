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
        $str = <<<EOF
<?php

namespace ${testNamespace};

class ${className}Test
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
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        $result = "";
        foreach ($methods as $method) {
            $string = $this->writeDocBlock();
            if ($method->isConstructor()) {
                $string .= $this->writeConstructor($reflection, $method);
            } else {
                $string .= $this->writePublicFunction($reflection, $method);
            }
            $result .= $string;
        }

        return $result;
    }

    /**
     * Writes the class constructor
     *
     * @param  \ReflectionClass $reflection
     * @param  \ReflectionMethod $method
     *
     * @return string
     */
    public function writeConstructor($reflection, $method)
    {
        $className = $reflection->getShortName();
        $objectName = lcfirst($className);

        // $parameters = $method->getParameters();
        // foreach ($parameters as $parameter) {
        //     $class = $parameter->getClass();
        //     $name = $parameter->getName();
        // }

        // ReflectionParameter#5

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
}
