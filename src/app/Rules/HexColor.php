<?php

namespace App\Rules;

use LVR\Colour\Hex;

class HexColor extends Hex
{

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalidFormat';
    }
}
