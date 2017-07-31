<?php
namespace ArturZeAlves\MosesBundle\Command;

use ArturZeAlves\Moses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class MosesCommand extends Command
{
    /**
     * Configures the Command
     */
    public function configure()
    {
        $this
            ->setName('lf:unit')
            ->setDescription('Generates a unit test class')
            ->addArgument(
                'class',
                InputArgument::REQUIRED,
                'Class ok'
            )
        ;
    }

    /**
     * Executes the Command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('class');
        $gen = new Moses();
        if (!file_exists($filename)) {
            printf("Invalid file %s\n", $filename);
            die;
        }

        $className = $gen->getClassName($filename);
        $namespace = $gen->getNamespace($filename);
        $testNamespace = $gen->guessTestNamespace($namespace);

        $dialog = $this->getHelperSet()->get('dialog');
        $output->writeln("");
        $output->writeln("Namespace: ".$testNamespace);
        if (!$dialog->askConfirmation(
            $output,
            "<question>Is this namespace correct? [y/n]</question>",
            false
        )) {
            $output->writeln("Type the correct namespace bellow");
            $testNamespace = $dialog->ask($output, 'Namespace: ', $testNamespace);
        }

        $reflection = new \ReflectionClass($namespace.'\\'.$className);
        echo $gen->convert($reflection, $testNamespace);
    }
}
