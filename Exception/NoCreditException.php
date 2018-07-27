<?php

namespace Eoko\CreditBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NoCreditException extends HttpException
{
    public function __construct(\Exception $e = null)
    {
        parent::__construct(403, 'You are not enough credit', $e);
    }
}
