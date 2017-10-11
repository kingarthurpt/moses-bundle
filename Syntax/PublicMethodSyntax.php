<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * PublicMethodSyntax
 */
class PublicMethodSyntax extends MethodSyntax
{
    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        $result = "";

        $methods = $this->getClassFunctions(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if ($method->isConstructor()) {
                continue;
            }
            $result .= $this->docBlockSyntax->getText();
            $result .= $this->getMethod($method->getName());
        }

        return $result;
    }

    /**
     * Writes a function
     *
     * @param string $name
     *
     * @return string
     */
    protected function getMethod($name)
    {
        $className = $this->reflection->getName();
        $objectName = lcfirst($className);
        $method = ucwords($name);

        // var_dump($method->getParameters());
        // ReflectionParameter#5

        return <<<EOF
    public function test${method}()
    {

    }\n\n
EOF;
    }
}
