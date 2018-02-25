<?php

namespace App\Validator;

use App\Hash;
use App\Model\Block;

class HashValidator implements BlockValidatorInterface
{
    public function validate(Block $previous, Block $block): bool
    {
        return Hash::hashBlock($block) === $block->getHash()
            && Hash::isHashValid($block->getHash(), $block->getDifficulty())
            && $previous->getHash() === $block->getPreviousHash();
    }
}
