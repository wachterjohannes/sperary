<?php

namespace App;

use App\Model\Block;
use App\Model\GenesisBlock;
use App\Storage\BlockStorageInterface;

class Sperary
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
     * @var BlockStorageInterface
     */
    private $storage;

    /**
     * @var Block
     */
    private $latestBlock;

    public function __construct(BlockStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function applyBlock(Block $block): Block
    {
        if (!$this->isChainValid($block)) {
            throw new \Exception('Chain not valid');
        }

        $this->latestBlock = $block;
        $this->storage->store($this->latestBlock->getHash(), $this->latestBlock);
        $this->storage->storeTag('latest', $this->latestBlock->getHash());

        return $this->latestBlock;
    }

    public function replaceChain(Block $latestBlock)
    {
        if ($latestBlock->getIndex() < $this->getLatestBlock()->getIndex() || !$this->isChainValid($latestBlock)) {
            throw new \Exception('Chain not valid');
        }

        // TODO sync previous blocks
        // TODO use accumulated difficulty
        $this->latestBlock = $latestBlock;
    }

    public function isChainValid(Block $latestBlock): bool
    {
        $block = $latestBlock;
        while ($block) {
            $previous = $this->storage->load($block->getPreviousHash());
            if ($previous && !$this->isValid($previous, $block)) {
                return false;
            }

            $block = $previous;
        }

        return true;
    }

    public function getLatestBlock(): Block
    {
        if ($this->latestBlock) {
            return $this->latestBlock;
        }

        $this->latestBlock = $this->storage->loadTag('latest');
        if (!$this->latestBlock) {
            $this->applyBlock(new GenesisBlock());
        }

        return $this->latestBlock;
    }

    public function getDifficulty(): int
    {
        if ($this->getLatestBlock()->getIndex() % self::DIFFICULTY_ADJUSTMENT_INTERVAL === 0 && $this->getLatestBlock()
                ->getIndex() !== 0) {
            return $this->getAdjustedDifficulty();
        }

        return $this->latestBlock->getDifficulty();
    }

    private function getAdjustedDifficulty(): int
    {
        $block = $this->getLatestBlock();
        for ($i = 0; $i < self::DIFFICULTY_ADJUSTMENT_INTERVAL; $i++) {
            if (!$block->getPreviousHash()) {
                break;
            }

            $block = $this->storage->load($block->getPreviousHash());
        }

        $expected = self::BLOCK_GENERATION_INTERVAL * self::DIFFICULTY_ADJUSTMENT_INTERVAL;
        $taken = $this->getLatestBlock()->getTimestamp() - $block->getTimestamp();

        if ($taken < $expected / 2) {
            return $this->getLatestBlock()->getDifficulty() + 1;
        } else if ($taken > $expected * 2) {
            return min($this->getLatestBlock()->getDifficulty() - 1, 0);
        }

        return $block->getDifficulty();
    }

    private function isValid(Block $previous, Block $block): bool
    {
        if (!Hash::hashBlock($block) === $block->getHash()) {
            return false;
        } elseif (!Hash::isHashValid($block->getHash(), $block->getDifficulty())) {
            return false;
        } elseif (!($previous->getTimestamp() - 60 < $block->getTimestamp()) && $block->getTimestamp() - 60 < time()) {
            return false;
        } elseif ($previous->getIndex() + 1 !== $block->getIndex()) {
            return false;
        } else if ($previous->getHash() !== $block->getPreviousHash()) {
            return false;
        }

        return true;
    }
}
