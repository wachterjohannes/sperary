<?php

namespace App;

use App\Model\Block;
use App\Model\GenesisBlock;
use App\Storage\BlockStorageInterface;

class Sperary
{
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

    public function generateBlock(string $data, int $proof = null): Block
    {
        $latestBLock = $this->getLatestBlock();
        $this->latestBlock = new Block($latestBLock->getIndex() + 1, $proof, time(), $latestBLock->getHash(), $data);
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
        $this->latestBlock = $latestBlock;
    }

    public function isChainValid(Block $latestBlock): bool
    {
        $block = $latestBlock;
        while ($block->getPreviousHash()) {
            $previous = $this->storage->load($block->getPreviousHash());
            if (!$this->isValid($previous, $block)) {
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
            $this->latestBlock = new GenesisBlock();
            $this->storage->store($this->latestBlock->getHash(), $this->latestBlock);
            $this->storage->storeTag('latest', $this->latestBlock->getHash());
        }

        return $this->latestBlock;
    }

    private function isValid(Block $previous, Block $block): bool
    {
        if ($previous->getIndex() + 1 !== $block->getIndex()) {
            return false;
        } else if ($previous->getHash() !== $previous->getPreviousHash()) {
            return false;
        }

        return true;
    }
}
