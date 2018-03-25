<?php

namespace Psl\Mvc;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;

class MvcListeners extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
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
        $siteSettings = $serviceLocator->get('Omeka\SiteSettings');

        $routeMatch = $event->getRouteMatch();
        $matchedRouteName = $routeMatch->getMatchedRouteName();
        if ('site/item-set' !== $matchedRouteName) {
            return;
        }

        $siteSlug = $routeMatch->getParam('site-slug');
        $themeSettings = $siteSettings->get("theme_settings_$siteSlug");
        if (!array_key_exists('search_page_id', $themeSettings)) {
            return;
        }

        $searchPageId = $themeSettings['search_page_id'];
        if (empty($searchPageId)) {
            return;
        }

        $routeMatch = new RouteMatch([
            '__NAMESPACE__' => 'Search\Controller',
            '__SITE__' => true,
            'controller' => 'Search\Controller\Index',
            'action' => 'search',
            'site-slug' => $siteSlug,
            'id' => $searchPageId,
        ]);
        $routeMatch->setMatchedRouteName('search-page-' . $searchPageId);
        $event->setRouteMatch($routeMatch);

        $itemSetId = $routeMatch->getParam('item-set-id');

        $query = $event->getRequest()->getQuery();
        $query->set('itemSet', ['ids' => [$itemSetId]]);
    }
}
