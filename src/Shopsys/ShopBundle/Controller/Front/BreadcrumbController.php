<?php

namespace Shopsys\ShopBundle\Controller\Front;

use Shopsys\ShopBundle\Component\Breadcrumb\BreadcrumbResolver;
use Shopsys\ShopBundle\Component\Controller\FrontBaseController;

class BreadcrumbController extends FrontBaseController
{
    /**
     * @var \Shopsys\ShopBundle\Component\Breadcrumb\BreadcrumbResolver
     */
    private $breadcrumbResolver;

    public function __construct(
        BreadcrumbResolver $breadcrumbResolver
    ) {
        $this->breadcrumbResolver = $breadcrumbResolver;
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     */
    public function indexAction($routeName, array $routeParameters = [])
    {
        $breadcrumbItems = $this->breadcrumbResolver->resolveBreadcrumbItems($routeName, $routeParameters);

        return $this->render('@ShopsysShop/Front/Inline/Breadcrumb/breadcrumb.html.twig', [
            'breadcrumbItems' => $breadcrumbItems,
        ]);
    }
}