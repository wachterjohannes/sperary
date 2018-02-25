<?php

namespace App;

use App\Model\Block;
use App\Model\GenesisBlock;
use App\Storage\BlockStorageInterface;
use App\Validator\BlockValidatorInterface;

class Sperary
{
    /**
     * @var BlockStorageInterface
     */
    private $storage;

    /**
     * @var BlockValidatorInterface
     */
    private $blockValidator;

    /**
     * @var Block
     */
    private $latestBlock;

    public function __construct(BlockStorageInterface $storage, BlockValidatorInterface $blockValidator)
    {
        $this->storage = $storage;
        $this->blockValidator = $blockValidator;
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
            if ($previous && !$this->blockValidator->validate($previous, $block)) {
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
}
