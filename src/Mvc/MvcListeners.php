<?php
namespace Psl\Mvc;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;

class MvcListeners extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'redirectItemSetToSearch'],
            -10
        );
    }

    public function redirectItemSetToSearch(MvcEvent $event)
    {
        $serviceLocator = $event->getApplication()->getServiceManager();
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');

        $routeMatch = $event->getRouteMatch();
        $matchedRouteName = $routeMatch->getMatchedRouteName();
        if ('site/item-set' !== $matchedRouteName) {
            return;
        }

        $searchMainPage = $siteSettings->get('search_main_page');
        if (empty($searchMainPage)) {
            return;
        }

        $siteSlug = $routeMatch->getParam('site-slug');

        $routeMatch = new RouteMatch([
            '__NAMESPACE__' => 'Search\Controller',
            '__SITE__' => true,
            'controller' => 'Search\Controller\IndexController',
            'action' => 'search',
            'site-slug' => $siteSlug,
            'id' => $searchMainPage,
        ]);
        $routeMatch->setMatchedRouteName('search-page-' . $searchMainPage);
        $event->setRouteMatch($routeMatch);

        $itemSetId = $routeMatch->getParam('item-set-id');

        $query = $event->getRequest()->getQuery();
        $query->set('itemSet', ['ids' => [$itemSetId]]);
    }
}
