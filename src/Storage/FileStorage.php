<?php

namespace App\Storage;

use App\Model\Block;

class FileStorage implements BlockStorageInterface
{
    /**
     * @var string
     */
    private $directory;

    public function __construct()
    {
        $this->directory = __DIR__ . '/../../var/data';

        if (!file_exists($this->directory)) {
            mkdir($this->directory);
        }
    }

    public function store(string $hash, Block $block): bool
    {
        file_put_contents(sprintf('%s/%s.block', $this->directory, $hash), serialize($block));

        return true;
    }

    public function storeTag(string $tag, string $hash): bool
    {
        file_put_contents(sprintf('%s/%s.tag', $this->directory, $tag), $hash);

        return true;
    }

    public function load(string $hash): ?Block
    {
        if (!file_exists(sprintf('%s/%s.block', $this->directory, $hash))) {
            return null;
        }

        return unserialize(file_get_contents(sprintf('%s/%s.block', $this->directory, $hash)));
    }

    public function loadTag(string $tag): ?Block
    {
        if (!file_exists(sprintf('%s/%s.tag', $this->directory, $tag))) {
            return null;
        }

        return $this->load(file_get_contents(sprintf('%s/%s.tag', $this->directory, $tag)));
    }
}
