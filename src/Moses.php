<?php

namespace ArturZeAlves;

class Moses
{
    public function getClassName($filename)
    {
        return str_replace(".php", "", basename($filename));
    }

    public function getNamespace($filename)
    {
        $src = file_get_contents($filename);
        if (preg_match('#^namespace\s+(.+?);$#sm', $src, $match)) {
            return $match[1];
        }

        return null;
    }

    public function guessTestNamespace($namespace)
    {
        $parts = explode("\\", $namespace);
        array_splice($parts, -1, 0, "Tests");

        return implode("\\", $parts);
    }

    public function convert($reflection, $testNamespace)
    {
        return $this->writeClass($reflection, $testNamespace);
    }

    private function writeClass($reflection, $testNamespace)
    {
        // todo: guess test class namespace
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

    private function convertPublicFunctions($reflection)
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

    public function writeConstructor($reflection, $method)
    {
        $className = $reflection->getShortName();
        $objectName = lcfirst($className);

        $parameters = $method->getParameters();
        foreach ($parameters as $parameter) {
            $class = $parameter->getClass();
            $name = $parameter->getName();


        }
        die;
        // ReflectionParameter#5

        return <<<EOF
    public function setUp()
    {
        $${objectName} = new {$className}();
    }\n\n
EOF;
    }

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
