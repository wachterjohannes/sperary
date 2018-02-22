<?php

namespace App\Model;

use App\Hash;

class Block
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * @var int
     */
    protected $index;

    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var int
     */
    protected $nonce;

    /**
     * @var int
     */
    protected $difficulty;

    /**
     * @var string
     */
    protected $previousHash;

    public function __construct(
        string $hash,
        int $index,
        int $nonce,
        int $difficulty,
        int $timestamp,
        ?string $previousHash,
        string $data
    ) {
        $this->hash = $hash;
        $this->index = $index;
        $this->nonce = $nonce;
        $this->difficulty = $difficulty;
        $this->timestamp = $timestamp;
        $this->previousHash = $previousHash;
        $this->data = $data;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getNonce(): int
    {
        return $this->nonce;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function getPreviousHash(): string
    {
        return $this->previousHash;
    }

    public function json(): array
    {
        return [
            'hash' => $this->getHash(),
            'index' => $this->getIndex(),
            'timestamp' => $this->getTimestamp(),
            'proof' => $this->getProof(),
            'previousHash' => $this->getPreviousHash(),
            'data' => $this->getData(),
        ];
    }
}
