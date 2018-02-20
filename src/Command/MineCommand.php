<?php

namespace App\Command;

use App\Sperary;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MineCommand extends Command
{
    /**
     * @var Sperary
     */
    private $sperary;

    public function __construct(Sperary $blockChain)
    {
        parent::__construct('sperary:mine');

        $this->sperary = $blockChain;
    }

    protected function configure()
    {
        $this->addArgument('address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $block = $this->sperary->getLatestBlock();

        $proof = 0;
        while (!$this->sperary->validProof($block->getProof(), $proof)) {
            $proof++;
        }

        $block = $this->sperary->mine($proof, $input->getArgument('address'));

        $output->writeln(
            sprintf(
                'Block %s mined at %s with %s',
                $block->getIndex(),
                (new \DateTime('@' . $block->getTimestamp()))->format(\DateTime::RFC3339),
                $block->getProof()
            )
        );

        $table = new Table($output);
        $table->setHeaders(['sender', 'recipient', 'amount']);
        foreach ($block->getTransactions() as $transaction) {
            $table->addRow([$transaction->getSender(), $transaction->getRecipient(), $transaction->getAmount()]);
        }

        $table->render();
    }
}
