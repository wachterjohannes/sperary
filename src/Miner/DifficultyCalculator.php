<?php

namespace App\Miner;

use App\Sperary;
use App\Storage\BlockStorageInterface;

class DifficultyCalculator
{
    /**
     * In seconds
     */
    const BLOCK_GENERATION_INTERVAL = 30;

    /**
     * In blocks
     */
    const DIFFICULTY_ADJUSTMENT_INTERVAL = 5;

    /**
     * @var Sperary
     */
    private $sperary;

    /**
     * @var BlockStorageInterface
     */
    private $storage;

    public function __construct(Sperary $sperary, BlockStorageInterface $storage)
    {
        $this->sperary = $sperary;
        $this->storage = $storage;
    }

    public function getDifficulty(): int
    {
        $latestBlock = $this->sperary->getLatestBlock();
        if ($latestBlock->getIndex() % self::DIFFICULTY_ADJUSTMENT_INTERVAL === 0 && $latestBlock->getIndex() !== 0) {
            return $this->getAdjustedDifficulty();
        }

        return $latestBlock->getDifficulty();
    }

    private function getAdjustedDifficulty(): int
    {
        $latestBlock = $block = $this->sperary->getLatestBlock();
        for ($i = 0; $i < self::DIFFICULTY_ADJUSTMENT_INTERVAL; $i++) {
            if (!$block->getPreviousHash()) {
                break;
            }

            $block = $this->storage->load($block->getPreviousHash());
        }

        $expected = self::BLOCK_GENERATION_INTERVAL * self::DIFFICULTY_ADJUSTMENT_INTERVAL;
        $taken = $latestBlock->getTimestamp() - $block->getTimestamp();

        if ($taken < $expected / 2) {
            return $latestBlock->getDifficulty() + 1;
        } else if ($taken > $expected * 2) {
            return min($latestBlock->getDifficulty() - 1, 0);
        }

        return $block->getDifficulty();
    }
}
