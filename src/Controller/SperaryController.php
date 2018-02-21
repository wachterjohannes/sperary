<?php

namespace App\Controller;

use App\Sperary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SperaryController extends Controller
{
    /**
     * @Route("/api/sperary", name="sperary")
     */
    public function index(Sperary $sperary)
    {
        $block = $sperary->getLatestBlock();

        return $this->json(
            [
                'name' => 'sperary',
                'version' => '0.1',
                'tagline' => 'Data never sleeps',
                'block' => [
                    'hash' => $sperary->hash($block),
                    'index' => $block->getIndex(),
                    'timestamp' => $block->getTimestamp(),
                ],
            ]
        );
    }
}
