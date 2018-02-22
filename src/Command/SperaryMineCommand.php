<?php

namespace App\Command;

use App\Hash;
use App\Model\Block;
use App\Sperary;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SperaryMineCommand extends Command
{
    protected static $defaultName = 'sperary:mine';

    /**
     * @var Sperary
     */
    private $sperary;

    /**
     * SperaryMineCommand constructor.
     * @param Sperary $sperary
     */
    public function __construct(Sperary $sperary)
    {
        parent::__construct();

        $this->sperary = $sperary;
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
            $block = $this->mine();

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

    private function mine(): Block
    {
        $latestBlock = $this->sperary->getLatestBlock();
        $difficulty = $this->sperary->getDifficulty();

        $hash = null;
        $blockData = null;
        $nonce = 0;
        while (!$hash || !Hash::isHashValid($hash, $difficulty)) {
            $blockData = [
                'index' => $latestBlock->getIndex() + 1,
                'nonce' => $nonce,
                'difficulty' => $difficulty,
                'timestamp' => time(),
                'previousHash' => $latestBlock->getHash(),
                'data' => ''
            ];
            $hash = Hash::hashBlockData(
                $blockData['index'],
                $blockData['previousHash'],
                $blockData['timestamp'],
                $blockData['data'],
                $blockData['difficulty'],
                $blockData['nonce']
            );
            $nonce++;
        }

        $block = new Block(
            $hash,
            $blockData['index'],
            $blockData['nonce'],
            $blockData['difficulty'],
            $blockData['timestamp'],
            $blockData['previousHash'],
            $blockData['data']
        );

        return $this->sperary->applyBlock($block);
    }
}
