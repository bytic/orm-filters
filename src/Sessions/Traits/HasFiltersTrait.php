<?php

namespace Nip\Records\Filters\Sessions\Traits;

use Nip\Records\Filters\AbstractFilter;
use Nip\Records\Filters\Column\AbstractFilter as AbstractColumnFilter;
use Nip\Records\Filters\FilterInterface;

/**
 * Trait HasFiltersTrait
 * @package Nip\Records\Filters\Traits
 */
trait HasFiltersTrait
{
    /**
     * @var AbstractFilter[]|AbstractColumnFilter[]
     */
    protected $filters = [];

    /**
     * @param AbstractFilter|FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    /**
     * @return AbstractFilter[]|AbstractColumnFilter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param AbstractFilter[]|AbstractColumnFilter[] $filters
     */
    public function setFilters($filters)
    {
        foreach ($filters as $filter) {
            $filter->setSession($this);
            $this->addFilter($filter);
        }
    }

    public function resetFilters()
    {
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            $filter->setValue(null);
        }
    }
}
