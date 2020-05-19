<?php

namespace ArturZeAlves\Command;

use ArturZeAlves\Moses;
use ArturZeAlves\Parser\PHPFileParser;
use ArturZeAlves\Prediction\Prediction;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class MosesCommand extends Command
// class MosesCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Moses
     */
    private $moses;

    /**
     * @var PHPFileParser
     */
    private $parser;

    /**
     * @var Prediction
     */
    private $prediction;

    /**
     * @param Moses         $moses
     * @param PHPFileParser $fileParser
     * @param Prediction    $prediction
     */
    public function __construct(Moses $moses, PHPFileParser $fileParser, Prediction $prediction)
    {
        $this->moses = $moses;
        $this->parser = $fileParser;
        $this->prediction = $prediction;

        parent::__construct();
    }

    /**
     * Configures the Command.
     */
    public function configure()
    {
        $this
            ->setName('moses:generate-test-class')
            ->setDescription('Generates a unit test class')
            ->addArgument(
                'class',
                InputArgument::REQUIRED,
                'The class that needs to be tested'
            )
            ->addOption(
               'sandbox',
               null,
               InputOption::VALUE_NONE,
               'If set, Moses will not write a new file and instead will write all output to the terminal'
            )
            ->addOption(
               'no-confirmation',
               null,
               InputOption::VALUE_NONE,
               'If set, Moses will not ask you anything and will follow God\'s will'
            )
        ;
    }

    /**
     * Executes the Command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $filesystem = $this->moses->getWriter()->getFilesystem();

        $filename = $input->getArgument('class');
        if (!file_exists($filename)) {
            throw new FileNotFoundException();
        }

        $className = $this->parser->getClassName($filename);
        $namespace = $this->parser->getNamespace($filename);
        $testNamespace = $this->prediction->guessTestNamespace($namespace);

        if (!$this->input->getOption('no-confirmation')) {
            do {
                $testNamespace = $this->askAndRead(
                    'Namespace: ',
                    $testNamespace,
                    'Is this namespace correct?',
                    'Type the correct namespace below'
                );
                var_dump($testNamespace);
            } while (!$testNamespace);
        }

        $reflection = new \ReflectionClass($namespace.'\\'.$className);
        $prophecies = $this->moses->prophesize($reflection, $testNamespace);

        if ($input->getOption('sandbox')) {
            $output->write($prophecies);
            die;
        }

        $outputFile = $this->prediction->guessTestFilePath($filename, $className);
        $this->saveFile($filesystem, $outputFile, $prophecies, $filename, $className);

        return 0;
    }

    /**
     * Saves the test class in a file.
     *
     * @param Filesystem $filesystem
     * @param string     $outputFile
     * @param string     $prophecies
     * @param string     $filename
     * @param string     $className
     */
    private function saveFile(Filesystem $filesystem, $outputFile, $prophecies, $filename, $className)
    {
        if (!$this->input->getOption('no-confirmation')) {
            $outputFile = $this->askAndRead(
                'Test class file path: ',
                $outputFile,
                'Is this file path correct?',
                'Type the correct file path below'
            );
        }

        $outputDir = dirname($outputFile);
        if (!file_exists($outputDir)) {
            $this->output->writeln('Creating directory '.$outputDir);
            $filesystem->mkdir($outputDir);
        }

        if (!$this->input->getOption('no-confirmation')) {
            if (file_exists($outputFile)) {
                $answer = $this->askAndRead(
                    'Saving file to: ',
                    $outputFile,
                    'The file already exists. Do you want to replace it? '
                );

                if (!$answer) {
                    $this->output->writeln('Nothing to do here then...');
                    die;
                }
            }
        }

        $this->output->writeln('Creating file '.$outputFile);
        file_put_contents($outputFile, $prophecies);
    }

    /**
     * Asks the user a question and reads the users input if the answer is negative.
     *
     * @param string $label
     * @param string $var
     * @param string $question
     * @param string $hint
     *
     * @return string
     */
    private function askAndRead($label, $var, $question, $hint = '')
    {
        $helper = $this->getHelper('question');
        // $dialog = $this->getHelperSet()->get('question');

        $this->output->writeln('');
        $this->output->writeln($label.$var);
        $question = new ConfirmationQuestion('<question>'.$question.' [y/n]</question>', false);
        if (!$helper->ask($this->input, $this->output, $question)) {
            var_dump('aqui');
            // return 0;
            // }
            // if (!$dialog->askConfirmation(
            //     $this->output,
            //     '<question>'.$question.' [y/n]</question>',
            //     false
            // )) {
            if (empty($hint)) {
                return false;
            }

            $this->output->writeln($hint);
            $question = new ConfirmationQuestion($label.$var, false);
            $var = $helper->ask($this->input, $this->output, $question);
            // $var = $dialog->ask($this->output, $label, $var);
        }

        return $var;
    }
}
