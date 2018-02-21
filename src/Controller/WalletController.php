<?php

namespace App\Controller;

use App\Wallet\BalanceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends Controller
{
    /**
     * @Route("/api/wallet/{publicKey}", name="wallet")
     */
    public function index(string $address, BalanceCalculator $balanceCalculator)
    {
        return $this->json(
            [
                'balance' => $balanceCalculator->getBalance($address),
            ]
        );
    }
}
