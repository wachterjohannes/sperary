<?php

namespace App\Network;

interface CommandInterface
{
    public function getName();
    public function getPayload();
}
