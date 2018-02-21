<?php

namespace App;

use App\Model\Block;

final class Hash
{
    public static function hashBlock(Block $block): string
    {
        return self::hash(serialize($block));
    }

    public static function hash(string $content): string
    {
        return hash('sha256', $content);
    }

    private function __construct()
    {
    }
}
