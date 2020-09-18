<?php

namespace Nip\Records\Filters\Controllers;

use Nip\Records\Filters\Records\HasFiltersRecordsTrait;

/**
 * Trait HasFiltersTrait
 * @package Nip\Records\Filters\Controllers
 *
 * @method HasFiltersRecordsTrait getModelManager
 */
trait HasFiltersTrait
{

    /**
     * @param null $session
     * @return \Nip\Records\Filters\Sessions\Session
     */
    protected function getRequestFilters($session = null)
    {
        $filterManager = $this->getModelManager()->getFilterManager();
        $filterManager->setRequest($this->getRequest());
        return $filterManager->getSession($session);
    }
}