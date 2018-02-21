<?php

namespace App\Controller;

use App\Sperary;
use App\Storage\BlockStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SperaryController extends Controller
{
    /**
     * @Route("/block", name="latest_block")
     */
    public function latestBlock(Sperary $sperary): Response
    {
        return $this->json($sperary->getLatestBlock()->json());
    }

    /**
     * @Route("/block/{hash}", name="block")
     */
    public function block(string $hash, BlockStorageInterface $storage): Response
    {
        return $this->json($storage->load($hash)->json());
    }

    /**
     * @Route("/block", name="mine_block", methods={"POST"})
     */
    public function mine(Request $request, Sperary $sperary): Response
    {
        return $this->json($sperary->generateBlock($request->get('data', '')));
    }
}
