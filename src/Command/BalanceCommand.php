<?php

namespace App\Command;

use App\Sperary;
use App\Storage\BlockStorageInterface;
use App\Wallet\BalanceCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends Command
{
    /**
     * @var BalanceCalculator
     */
    private $balanceCalculator;

    public function __construct(BalanceCalculator $balanceCalculator)
    {
        parent::__construct('sperary:balance');

        $this->balanceCalculator = $balanceCalculator;
    }

    protected function configure()
    {
        $this->addArgument('address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->balanceCalculator->getBalance($input->getArgument('address')));
    }
}
