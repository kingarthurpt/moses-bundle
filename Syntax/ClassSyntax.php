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
     * @var ProtectedMethodSyntax
     */
    protected $protectedMethodSyntax;

    /**
     * @var PrivateMethodSyntax
     */
    protected $privateMethodSyntax;

    /**
     * @var ClassPropertySyntax
     */
    protected $classPropertySyntax;

    /**
     * Constructor
     *
     * @param UseSyntax             $useSyntax
     * @param DocBlockSyntax        $docBlockSyntax
     * @param SetUpSyntax           $setUpSyntax
     * @param PublicMethodSyntax    $publicMethodSyntax
     * @param ProtectedMethodSyntax $protectedMethodSyntax
     * @param PrivateMethodSyntax   $privateMethodSyntax
     * @param ClassPropertySyntax   $classPropertySyntax
     */
    public function __construct(
        UseSyntax $useSyntax,
        DocBlockSyntax $docBlockSyntax,
        SetUpSyntax $setUpSyntax,
        PublicMethodSyntax $publicMethodSyntax,
        ProtectedMethodSyntax $protectedMethodSyntax,
        PrivateMethodSyntax $privateMethodSyntax,
        ClassPropertySyntax $classPropertySyntax
    ) {
        $this->useSyntax = $useSyntax;
        $this->docBlockSyntax = $docBlockSyntax;
        $this->setUpSyntax = $setUpSyntax;
        $this->publicMethodSyntax = $publicMethodSyntax;
        $this->protectedMethodSyntax = $protectedMethodSyntax;
        $this->privateMethodSyntax = $privateMethodSyntax;
        $this->classPropertySyntax = $classPropertySyntax;
    }

    /**
     * @{inheritDoc}
     */
    public function getText()
    {
        $setUp = $this->setUpSyntax
            ->setReflection($this->reflection)
            ->getText();
        $publicFunctions = $this->publicMethodSyntax
            ->setReflection($this->reflection)
            ->getText();
        $protectedFunctions = $this->protectedMethodSyntax
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
{$this->classPropertySyntax->getText()}
{$setUp}
{$publicFunctions}
{$protectedFunctions}
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
