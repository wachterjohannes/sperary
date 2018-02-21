<?php

namespace App\Controller;

use App\Sperary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends Controller
{
    /**
     * @Route("/api/transactions", name="transaction_create", methods={"POST"})
     */
    public function index(Request $request, Sperary $sperary)
    {
        try {
            $transaction = $sperary->addTransaction(
                $request->get('sender'),
                $request->get('recipient'),
                $request->get('amount')
            );

            return $this->json(
                [
                    'error' => false,
                    'transactionId' => $transaction->getId(),
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
