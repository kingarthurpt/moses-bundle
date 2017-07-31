<?php
namespace ArturZeAlves\MosesBundle\Command;

use ArturZeAlves\MosesBundle\Moses;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MosesCommand extends ContainerAwareCommand
{
    /**
     * Configures the Command
     */
    public function configure()
    {
        $this
            ->setName('moses:generate-test-class')
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
        $moses = $this->getContainer()->get('artur_ze_alves_moses.moses');
        if (!file_exists($filename)) {
            printf("Invalid file %s\n", $filename);
            die;
        }

        $className = $moses->getClassName($filename);
        $namespace = $moses->getNamespace($filename);
        $testNamespace = $moses->guessTestNamespace($namespace);

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
        echo $moses->generate($reflection, $testNamespace);
    }
}
