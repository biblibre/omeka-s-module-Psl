<?php

namespace Psl;

use Zend\Mvc\MvcEvent;
use Omeka\Module\AbstractModule;
use Omeka\Permissions\Acl;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $this->allowAcl(Acl::ROLE_AUTHOR, 'ZoteroImport\Controller\Index');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'ZoteroImport\Api\Adapter\ZoteroImportAdapter');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'ZoteroImport\Api\Adapter\ZoteroImportItemAdapter');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'ZoteroImport\Entity\ZoteroImport');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'ZoteroImport\Entity\ZoteroImportItem');

        $this->allowAcl(Acl::ROLE_AUTHOR, 'CSVImport\Controller\Index');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'CSVImport\Api\Adapter\EntityAdapter');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'CSVImport\Api\Adapter\ImportAdapter');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'CSVImport\Entity\CSVImportEntity');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'CSVImport\Entity\CSVImportImport');
        $this->allowAcl(Acl::ROLE_AUTHOR, 'Omeka\Api\Adapter\ItemAdapter', 'batch_create');
    }

    protected function allowAcl($role, $resource, $privileges = null)
    {
        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        if ($acl->hasResource($resource)) {
            $acl->allow($role, $resource, $privileges);
        }
    }
}
