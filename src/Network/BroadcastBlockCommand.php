<?php

namespace App\Network;

use App\Model\Block;
use App\Model\Transaction;

class BroadcastBlockCommand implements CommandInterface
{
    /**
     * @var Block
     */
    private $block;

    public function __construct(Block $block)
    {
        $this->block = $block;
    }

    public function getName()
    {
        return 'block';
    }

    public function getPayload()
    {
        return [
            'index' => $this->block->getIndex(),
            'timestamp' => $this->block->getTimestamp(),
            'proof' => $this->block->getProof(),
            'previousHash' => $this->block->getPreviousHash(),
            'transactions' => array_map(
                function (Transaction $transaction) {
                    return [
                        'id' => $transaction->getId(),
                        'sender' => $transaction->getSender(),
                        'recipient' => $transaction->getRecipient(),
                        'amount' => $transaction->getAmount(),
                        'timestamp' => $transaction->getTimestamp(),
                    ];
                },
                $this->block->getTransactions()
            ),
        ];
    }
}
