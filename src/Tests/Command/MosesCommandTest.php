<?php

namespace ArturZeAlves\Tests\Command;

use ArturZeAlves\Command\MosesCommand;
use ArturZeAlves\Moses;
use ArturZeAlves\Parser\PHPFileParser;
use ArturZeAlves\Prediction\Prediction;

class MosesCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MosesCommand
     */
    private $command;

    /**
     * @var Moses
     */
    private $moses;

    /**
     * @var PHPFileParser
     */
    private $fileParser;

    /**
     * @var Prediction
     */
    private $prediction;


    protected function setUp(): void
    {
        $prophet = new \Prophecy\Prophet();
        $this->moses = $prophet->prophesize(Moses::class);
        $this->fileParser = $prophet->prophesize(PHPFileParser::class);
        $this->prediction = $prophet->prophesize(Prediction::class);

        $this->command = new MosesCommand($this->moses->reveal(), $this->fileParser->reveal(), $this->prediction->reveal());
    }


    /**
     *
     */
    public function testConfigure()
    {

    }

    /**
     *
     */
    public function testExecute()
    {

    }



    /**
     *
     */
    private function prophesizeSaveFile()
    {

    }

    /**
     *
     */
    private function prophesizeAskAndRead()
    {

    }


}