<?php

namespace App\Validator;

use App\Model\Block;

interface BlockValidatorInterface
{
    public function validate(Block $previous, Block $block): bool;
}
