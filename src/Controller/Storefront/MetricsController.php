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
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\APC;
use Prometheus\Storage\APCng;
use Prometheus\Storage\InMemory;


/**
 * 12/2022 created
 */
class MetricsController extends StorefrontController
{

    /**
     * @Route("/metrics",
     *     name="mcx.basic-example.metric",
     *     methods={"GET"},
     *     options={"seo"=false},
     *     defaults={"_routeScope"={"storefront"}},
     * )
     */
    public function examplePage(Request $request, SalesChannelContext $salesChannelContext): Response
    {
// $registry = \Prometheus\CollectorRegistry::getDefault();
// $registry = new CollectorRegistry(new APCng());
// $registry = new CollectorRegistry(new APC());
        $registry = new CollectorRegistry(new InMemory());




// single counter
        $registry
            ->getOrRegisterCounter('', 'some_quick_counter', 'just a quick measurement')
            ->inc();




        $counter = $registry->getOrRegisterCounter('test', 'some_counter', 'it increases', ['type']);
        $counter->incBy(3, ['blue']);

        $gauge = $registry->getOrRegisterGauge('test', 'some_gauge', 'it sets', ['type']);
        $gauge->set(2.5, ['blue']);

        $histogram = $registry->getOrRegisterHistogram('test', 'some_histogram', 'it observes', ['type'], [0.1, 1, 2, 3.5, 4, 5, 6, 7, 8, 9]);
        $histogram->observe(3.5, ['blue']);

        $summary = $registry->getOrRegisterSummary('test', 'some_summary', 'it observes a sliding window', ['type'], 84600, [0.01, 0.05, 0.5, 0.95, 0.99]);
        $summary->observe(5, ['blue']);


//// Manually register and retrieve metrics (these steps are combined in the getOrRegister... methods):
//$counterA = $registry->registerCounter('test', 'some_counter', 'it increases', ['type']);
//$counterA->incBy(3, ['blue']);
//
//// once a metric is registered, it can be retrieved using e.g. getCounter:
//$counterB = $registry->getCounter('test', 'some_counter');
//$counterB->incBy(2, ['red']);

// Expose the metrics:

        $renderer = new RenderTextFormat();
        $result = $renderer->render($registry->getMetricFamilySamples());

        return new Response($result, Response::HTTP_OK, ['Content-type' => RenderTextFormat::MIME_TYPE]);
    }


}