<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class Role extends Constraint
{
    public $message = 'Role value is incorrect.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
