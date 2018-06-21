<?php

namespace ActeurBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ActeurBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
