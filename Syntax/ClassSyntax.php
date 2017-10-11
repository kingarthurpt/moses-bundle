<?php

namespace ArturZeAlves\MosesBundle\Syntax;

/**
 * ClassSyntax
 */
class ClassSyntax implements SyntaxInterface
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var \ReflectionClass
     */
    protected $reflection;

    /**
     * @var UseSyntax
     */
    protected $useSyntax;

    /**
     * @var DocBlockSyntax
     */
    protected $docBlockSyntax;

    /**
     * @var SetUpSyntax
     */
    protected $setUpSyntax;

    /**
     * @var PublicMethodSyntax
     */
    protected $publicMethodSyntax;

    /**
     * @var PrivateMethodSyntax
     */
    protected $privateMethodSyntax;

    /**
     * Constructor
     *
     * @param UseSyntax           $useSyntax
     * @param DocBlockSyntax      $docBlockSyntax
     * @param SetUpSyntax         $setUpSyntax
     * @param PublicMethodSyntax  $publicMethodSyntax
     * @param PrivateMethodSyntax $privateMethodSyntax
     */
    public function __construct(
        UseSyntax $useSyntax,
        DocBlockSyntax $docBlockSyntax,
        SetUpSyntax $setUpSyntax,
        PublicMethodSyntax $publicMethodSyntax,
        PrivateMethodSyntax $privateMethodSyntax
    ) {
        $this->useSyntax = $useSyntax;
        $this->docBlockSyntax = $docBlockSyntax;
        $this->setUpSyntax = $setUpSyntax;
        $this->publicMethodSyntax = $publicMethodSyntax;
        $this->privateMethodSyntax = $privateMethodSyntax;
    }

    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        $setUp = $this->setUpSyntax
            ->setReflection($this->reflection)
            ->getText();
        $publicFunction = $this->publicMethodSyntax
            ->setReflection($this->reflection)
            ->getText();
        $privateFunctions = $this->privateMethodSyntax
            ->setReflection($this->reflection)
            ->getText();

        $str = <<<EOF
<?php

namespace {$this->namespace};

{$this->useSyntax->getText()}
class {$this->reflection->getShortName()}Test extends \PHPUnit_Framework_TestCase
{
{$setUp}
{$publicFunction}
{$privateFunctions}
}
EOF;

        return $str;
    }

    /**
     * Set class reflection
     *
     * @param string $reflection
     *
     * @return self
     */
    public function setReflection($reflection)
    {
        $this->reflection = $reflection;

        return $this;
    }

    /**
     * Set class namespace
     *
     * @param string $namespace
     *
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }
}
