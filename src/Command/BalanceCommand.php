<?php

namespace App\Command;

use App\Sperary;
use App\Storage\BlockStorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends Command
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
        parent::__construct('sperary:balance');

        $this->sperary = $sperary;
        $this->storage = $storage;
    }

    protected function configure()
    {
        $this->addArgument('address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $address =$input->getArgument('address');

        $balance = 0;
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

        $output->writeln($balance);
    }
}
