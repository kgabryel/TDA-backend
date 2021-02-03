<?php

namespace App\Traits;

trait Uuid
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
