<?php

namespace App\Validator;

use App\Hash;
use App\Model\Block;

class IndexValidator implements BlockValidatorInterface
{
    public function validate(Block $previous, Block $block): bool
    {
        return $previous->getIndex() + 1 === $block->getIndex();
    }
}
