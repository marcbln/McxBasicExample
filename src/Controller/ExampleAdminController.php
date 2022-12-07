<?php

namespace Mcx\BasicExample\Controller;


use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 12/2022 created
 * @RouteScope(scopes={"api"})
 */
class ExampleAdminController extends AbstractController
{
    /**
     * @Route("/api/v{version}/mcx/example",
     *     name="api.action.mcx.example",
     *     methods={"GET"},
     * )
     */
    public function exampleApiAction(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        return new JsonResponse([
            'some' => 'data',
        ]);
    }

}