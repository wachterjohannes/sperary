<?php

namespace App\Validator;

use App\Model\Block;

class AggregateBlockValidator implements BlockValidatorInterface
{
    /**
     * @var BlockValidatorInterface[]
     */
    private $validator;

    public function __construct()
    {
        $this->validator = [
            new HashValidator(),
            new IndexValidator(),
            new TimestampValidator(),
        ];
    }

    public function validate(Block $previous, Block $block): bool
    {
        foreach ($this->validator as $validator) {
            if (!$validator->validate($previous, $block)) {
                return false;
            }
        }

        return true;
    }
}
