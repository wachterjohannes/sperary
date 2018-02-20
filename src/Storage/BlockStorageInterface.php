<?php

namespace App\Storage;

use App\Model\Block;

interface BlockStorageInterface
{
    public function store(string $hash, Block $block): bool;
    public function storeTag(string $tag, string $hash): bool;
    public function load(string $hash): ?Block;
    public function loadTag(string $tag): ?Block;
}
