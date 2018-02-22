<?php

namespace App\Model;

use App\Hash;

class GenesisBlock extends Block
{
    public function __construct()
    {
        parent::__construct(Hash::hashBlockData(0, '', 0, '', 5, 0), 0, 0, 5, 0, '', '');
    }
}
