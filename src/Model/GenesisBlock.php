<?php

namespace App\Model;

class GenesisBlock extends Block
{
    public function __construct()
    {
        parent::__construct(0, [], 0, '');
    }
}
