<?php

namespace App\Model;

use App\Hash;

class Block
{
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
    protected $proof;

    /**
     * @var string
     */
    protected $previousHash;

    public function __construct(int $index, int $proof, int $timestamp, ?string $previousHash, string $data)
    {
        $this->index = $index;
        $this->proof = $proof;
        $this->timestamp = $timestamp;
        $this->previousHash = $previousHash;
        $this->data = $data;
    }

    public function getHash(): string
    {
        return Hash::hashBlock($this);
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

    public function getProof(): int
    {
        return $this->proof;
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
