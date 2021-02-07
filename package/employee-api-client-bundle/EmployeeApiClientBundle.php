<?php

namespace Package\EmployeeApiClientBundle;

use Package\EmployeeApiClientBundle\DependencyInjection\EmployeeApiClientExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EmployeeApiClientBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new EmployeeApiClientExtension();
    }
}
