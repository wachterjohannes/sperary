<?php

namespace App\Validator;

use App\Hash;
use App\Model\Block;

class TimestampValidator implements BlockValidatorInterface
{
    public function validate(Block $previous, Block $block): bool
    {
        return ($previous->getTimestamp() - 60 < $block->getTimestamp()) && $block->getTimestamp() - 60 < time();
    }
}
