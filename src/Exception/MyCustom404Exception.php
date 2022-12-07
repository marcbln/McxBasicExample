<?php

namespace Mcx\BasicExample\Exception;


use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * 12/2022 created
 */
class MyCustom404Exception extends ShopwareHttpException
{

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public function getErrorCode(): string
    {
        return "EXAMPLE__SOME_ERROR_CODE"; // to be used internally (not shown in the browser)
    }
}