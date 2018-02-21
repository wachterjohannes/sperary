<?php

namespace App\Model;


use Ramsey\Uuid\Uuid;

class Transaction
{
    public static function fromCommand(array $payload): Transaction
    {
        $transaction = new Transaction($payload['sender'], $payload['recipient'], $payload['amount']);
        $transaction->timestamp = $payload['timestamp'];

        return $transaction;
    }

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

    /**
     * @var int
     */
    private $timestamp;

    public function __construct(string $sender, string $recipient, float $amount)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->amount = $amount;

        $this->id = Uuid::uuid4()->toString();
        $this->timestamp = time();
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

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
