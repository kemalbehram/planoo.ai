<?php

namespace PromotionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PromotionBundle extends Bundle
{
    /* 3 WAYS TO GET THE SESSION FROM THE CONTROLLER
            $session = $this->container->get('session');
            $session = $this->get('session'); (which basically is a shortcut to 1)
            $session = $request->getSession();
        */
}
