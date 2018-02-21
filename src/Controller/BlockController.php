<?php

namespace App\Controller;

use App\Model\Transaction;
use App\Sperary;
use App\Storage\BlockStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends Controller
{
    /**
     * @Route("/api/blocks", name="block_mine", methods={"POST"})
     */
    public function mine(Request $request, Sperary $sperary)
    {
        try {
            $block = $sperary->mine($request->get('proof'), $request->get('recipient'));

            return $this->json(
                [
                    'error' => false,
                    'block' => [
                        'hash' => $sperary->hash($block),
                        'index' => $block->getIndex(),
                        'timestamp' => $block->getTimestamp(),
                    ],
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'error' => true,
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }

    /**
     * @Route("/api/blocks", name="block_latest", methods={"GET"})
     */
    public function getLatest(Sperary $sperary)
    {
        try {
            $block = $sperary->getLatestBlock();

            return $this->json(
                [
                    'error' => false,
                    'block' => [
                        'hash' => $sperary->hash($block),
                        'index' => $block->getIndex(),
                        'timestamp' => $block->getTimestamp(),
                    ],
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'error' => true,
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }

    /**
     * @Route("/api/blocks/{hash}", name="block_get", methods={"GET"})
     */
    public function get(string $hash, BlockStorageInterface $storage)
    {
        try {
            $block = $storage->load($hash);

            return $this->json(
                [
                    'error' => false,
                    'block' => [
                        'hash' => $hash,
                        'index' => $block->getIndex(),
                        'timestamp' => $block->getTimestamp(),
                    ],
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'error' => true,
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }

    /**
     * @Route("/api/blocks/{hash}/transactions", name="block_get_transactions", methods={"GET"})
     */
    public function getTransactions(string $hash, BlockStorageInterface $storage)
    {
        try {
            $block = $storage->load($hash);

            return $this->json(
                [
                    'error' => false,
                    'block' => [
                        'hash' => $hash,
                        'index' => $block->getIndex(),
                        'timestamp' => $block->getTimestamp(),
                    ],
                    'transactions' => array_map(
                        function (Transaction $transaction) {
                            return [
                                'id' => $transaction->getId(),
                                'sender' => $transaction->getSender(),
                                'recipient' => $transaction->getRecipient(),
                                'amount' => $transaction->getAmount(),
                            ];
                        },
                        $block->getTransactions()
                    ),
                ]
            );
        } catch (\Exception $exception) {
            return $this->json(
                [
                    'error' => true,
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }
}
