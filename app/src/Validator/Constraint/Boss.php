<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class Boss extends Constraint
{
    public $message = 'Boss id value is incorrect.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
