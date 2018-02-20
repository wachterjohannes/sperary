<?php

namespace App\Model;


class Transaction
{
    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var float
     */
    private $amount;

    public function __construct(string $sender, string $recipient, float $amount)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->amount = $amount;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
