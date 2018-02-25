<?php

namespace App\Miner;

use App\Hash;
use App\Model\Block;
use App\Sperary;

class Miner
{
    /**
     * @var Sperary
     */
    private $sperary;

    /**
     * @var DifficultyCalculator
     */
    private $difficulyCalculator;

    public function __construct(Sperary $sperary, DifficultyCalculator $difficulyCalculator)
    {
        $this->sperary = $sperary;
        $this->difficulyCalculator = $difficulyCalculator;
    }

    public function mine(): Block
    {
        $latestBlock = $this->sperary->getLatestBlock();
        $difficulty = $this->difficulyCalculator->getDifficulty();

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
