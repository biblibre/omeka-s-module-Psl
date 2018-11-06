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

    /**
     * Redirect item set show to the search page with item set set as url query.
     *
     * @param MvcEvent $event
     */
    public function redirectItemSetToSearch(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $matchedRouteName = $routeMatch->getMatchedRouteName();
        if ('site/item-set' !== $matchedRouteName) {
            return;
        }

        $services = $event->getApplication()->getServiceManager();
        $siteSettings = $services->get('Omeka\Settings\Site');
        $searchMainPage = $siteSettings->get('search_main_page');
        if (empty($searchMainPage)) {
            return;
        }

        $siteSlug = $routeMatch->getParam('site-slug');
        $itemSetId = $routeMatch->getParam('item-set-id');

        $params =  [
            '__NAMESPACE__' => 'Search\Controller',
            '__SITE__' => true,
            'controller' => \Search\Controller\IndexController::class,
            'action' => 'search',
            'site-slug' => $siteSlug,
            'id' => $searchMainPage,
        ];
        $routeMatch = new RouteMatch($params);
        $routeMatch->setMatchedRouteName('search-page-' . $searchMainPage);
        $event->setRouteMatch($routeMatch);

        $query = $event->getRequest()->getQuery();
        $query->set('itemSet', ['ids' => [$itemSetId]]);
    }
}
