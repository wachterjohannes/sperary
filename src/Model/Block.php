<?php

namespace App\Model;

class Block
{
    /**
     * @var int
     */
    private $index;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var Transaction[]
     */
    private $transactions;

    /**
     * @var int
     */
    private $proof;

    /**
     * @var string
     */
    private $previousHash;

    public function __construct(int $index, array $transactions, int $proof, string $previousHash)
    {
        $this->index = $index;
        $this->transactions = $transactions;
        $this->proof = $proof;
        $this->previousHash = $previousHash;

        $this->timestamp = time();
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getProof(): int
    {
        return $this->proof;
    }

    public function getPreviousHash(): string
    {
        return $this->previousHash;
    }
}
