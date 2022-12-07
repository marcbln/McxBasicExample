<?php

namespace Mcx\BasicExample\Controller\Storefront;


use Mcx\BasicExample\Exception\MyCustom404Exception;
use OpenApi\Annotations as OA;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 12/2022 created
 */
class ExampleStoreFrontController extends StorefrontController
{

    private EntityRepository $productRepository;
    private GenericPageLoaderInterface $genericPageLoader;

    public function __construct(EntityRepository $productRepository, GenericPageLoaderInterface $genericPageLoader)
    {
        $this->productRepository = $productRepository;
        $this->genericPageLoader = $genericPageLoader;
    }

    /**
     * @Route("/mcx/example-page",
     *     name="mcx.basic-example.example-page",
     *     methods={"GET"},
     *     options={"seo"=false},
     *     defaults={"_routeScope"={"storefront"}},
     * )
     */
    public function examplePage(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('createdAt', FieldSorting::DESCENDING));
        $criteria->setLimit(5);

        $latestAddedProducts = $this->productRepository->search($criteria, $salesChannelContext->getContext());


        return $this->renderStorefront("@McxBasicExample/storefront/page/example_page.html.twig", [
            'name'     => 'Marc',
//            'request'  => $request,
//            'context'  => $salesChannelContext->getContext(),
            'products' => $latestAddedProducts
        ]);
    }

    /**
     * @Route("/mcx/example-page-2",
     *     name="mcx.basic-example.example-page-2",
     *     methods={"GET"},
     *     options={"seo"=false},
     *     defaults={"_routeScope"={"storefront"}},
     * )
     */
    public function examplePage2(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $page = $this->genericPageLoader->load($request, $salesChannelContext);

        return $this->renderStorefront("@McxBasicExample/storefront/page/example_page_2.html.twig", [
            'page' => $page,
        ]);
    }

    /**
     * @OA\Get(path="/mcx/example-page",
     *     operationId="someUniqueId__usefulInDocs",
     *     description="some example storefront page",
     *     tags={"tag a", "tag b"},
     * )
     * @Route("/mcx/example-json",
     *     name="mcx.basic-example.example-json",
     *     methods={"GET"},
     *     options={"seo"=false},
     *     defaults={"_routeScope"={"storefront"}, "XmlHttpRequest"=true},
     * )
     */
    public function exampleJson(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('createdAt', FieldSorting::DESCENDING));
        $criteria->setLimit(3);
        $latestAddedProducts = $this->productRepository->search($criteria, $salesChannelContext->getContext());

        return new JsonResponse([
            'foo'      => 'bar!',
            'someData' => $latestAddedProducts->getElements(),
        ]);
    }

    /**
     * testing custom 404 exception (instead of normal NotFoundHttpException)
     *
     * @Route("/mcx/test-404",
     *     name="mcx.basic-example.test-404",
     *     options={"seo"=false},
     *     defaults={"_routeScope"={"storefront"}, "XmlHttpRequest"=true},
     * )
     */
    public function test404(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        throw new MyCustom404Exception("its is not here!");
    }

}