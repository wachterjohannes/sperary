<?php

namespace App\Model;


use Ramsey\Uuid\Uuid;

class Transaction
{
    /**
     * @var string
     */
    private $id;

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

        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
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
