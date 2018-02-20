<?php

namespace App;

use App\Model\Block;
use App\Model\GenesisBlock;
use App\Model\Transaction;
use App\Storage\BlockStorageInterface;

class Sperary
{
    const RESOLUTION_HASH = '2cb76';

    /**
     * @var Transaction[]
     */
    private $currentTransactions = [];

    /**
     * @var Block
     */
    private $latestBlock;

    /**
     * @var BlockStorageInterface
     */
    private $storage;

    public function __construct(BlockStorageInterface $storage)
    {
        $this->storage = $storage;

        $this->latestBlock = $this->storage->loadTag('latest');
        if (!$this->latestBlock) {
            $this->latestBlock = new GenesisBlock();

            $hash = $this->hash($this->latestBlock);
            $this->storage->store($hash, $this->latestBlock);
            $this->storage->storeTag('latest', $hash);
        }
    }

    public function mine(int $proof, string $recipient): Block
    {
        if (!$this->validProof($this->latestBlock->getProof(), $proof)) {
            throw new \Exception('Poof not valid');
        }

        $this->addTransaction('0', $recipient, 1);

        $this->latestBlock = new Block(
            $this->latestBlock->getIndex() + 1, $this->currentTransactions, $proof, $this->hash($this->latestBlock)
        );

        $this->currentTransactions = [];

        $hash = $this->hash($this->latestBlock);
        $this->storage->store($hash, $this->latestBlock);
        $this->storage->storeTag('latest', $hash);

        return $this->latestBlock;
    }

    public function addTransaction(string $sender, string $recipient, int $amount): Transaction
    {
        return $this->currentTransactions[] = new Transaction($sender, $recipient, $amount);
    }

    public function validProof(int $lastProof, int $proof): bool
    {
        $guessHash = hash('sha256', serialize($lastProof . $proof));

        return substr($guessHash, 0, 5) === self::RESOLUTION_HASH;
    }

    public function getLatestBlock()
    {
        return $this->latestBlock;
    }

    private function hash(Block $block)
    {
        return hash('sha256', serialize($block));
    }
}
