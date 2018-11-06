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

        $acl = $this->getServiceLocator()->get('Omeka\Acl');

        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'ZoteroImport\Controller\Index');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'ZoteroImport\Api\Adapter\ZoteroImportAdapter');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'ZoteroImport\Api\Adapter\ZoteroImportItemAdapter');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'ZoteroImport\Entity\ZoteroImport');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'ZoteroImport\Entity\ZoteroImportItem');

        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'CSVImport\Controller\Index');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'CSVImport\Api\Adapter\EntityAdapter');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'CSVImport\Api\Adapter\ImportAdapter');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'CSVImport\Entity\CSVImportEntity');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'CSVImport\Entity\CSVImportImport');
        $this->allowAcl($acl, Acl::ROLE_AUTHOR, 'Omeka\Api\Adapter\ItemAdapter', 'batch_create');
    }

    protected function allowAcl(Acl $acl, $role, $resource, $privileges = null)
    {
        if ($acl->hasResource($resource)) {
            $acl->allow($role, $resource, $privileges);
        }
    }
}
