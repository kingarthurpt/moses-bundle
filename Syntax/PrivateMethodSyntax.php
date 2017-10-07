<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * PrivateMethodSyntax
 */
class PrivateMethodSyntax extends MethodSyntax
{
    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        $result = "";
        // $this->privateMethodSyntax->setReflection($this->reflection);

        $methods = $this->getClassFunctions(\ReflectionMethod::IS_PRIVATE);
        foreach ($methods as $method) {
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
    private function prophesize${method}()
    {

    }\n\n
EOF;
    }
}
