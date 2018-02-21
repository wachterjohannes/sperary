<?php

namespace App\Wallet;

use App\Sperary;
use App\Storage\BlockStorageInterface;

class BalanceCalculator
{
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

    public function getBalance(string $address): float
    {
        $balance = 0.0;
        $block = $this->sperary->getLatestBlock();

        while ($block->getPreviousHash() !== '') {
            foreach ($block->getTransactions() as $transaction) {
                if ($transaction->getRecipient() === $address) {
                    $balance += $transaction->getAmount();
                } elseif ($transaction->getSender() === $address) {
                    $balance -= $transaction->getAmount();
                }
            }

            $block = $this->storage->load($block->getPreviousHash());
        }

        return $balance;
    }
}
