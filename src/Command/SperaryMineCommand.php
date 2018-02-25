<?php

namespace App\Command;

use App\Miner\Miner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SperaryMineCommand extends Command
{
    protected static $defaultName = 'sperary:mine';

    /**
     * @var Miner
     */
    private $miner;

    public function __construct(Miner $miner)
    {
        parent::__construct();

        $this->miner = $miner;
    }

    protected function configure()
    {
        $this->setDescription('Mine a new block for sperary blockchain.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        while (true) {
            $time = time();
            $block = $this->miner->mine();

            $io->success(
                sprintf(
                    'You mind a new block (%s) in %s seconds with hash %s and difficulty %s',
                    $block->getIndex(),
                    time() - $time,
                    $block->getHash(),
                    $block->getDifficulty()
                )
            );
        }
    }
}
