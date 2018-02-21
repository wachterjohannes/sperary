<?php

namespace App\Controller;

use App\Model\Block;
use App\Sperary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends Controller
{
    /**
     * @Route("/api/command/block", name="block_mine", methods={"POST"})
     */
    public function applyBlock(Request $request, Sperary $sperary)
    {
        $payload = $request->request->all();

        $block = Block::fromCommand($payload);

        try {
            $sperary->applyBlock($block);

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
        } catch (\Exception $e) {
            return $this->json(
                [
                    'error' => true,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
}
