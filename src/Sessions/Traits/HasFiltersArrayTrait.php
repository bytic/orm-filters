<?php

namespace Nip\Records\Filters\Sessions\Traits;

/**
 * Trait HasFiltersArrayTrait
 * @package Nip\Records\Filters\Sessions\Traits
 */
trait HasFiltersArrayTrait
{
    /**
     * @var null|array
     */
    protected $filtersArray = null;

    /**
     * @return array
     */
    public function __toArray()
    {
        return $this->getFiltersArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getFiltersArray();
    }

    /**
     * @return array
     */
    public function getFiltersArray()
    {
        if ($this->filtersArray === null) {
            $this->initFiltersArray();
        }

        return $this->filtersArray;
    }

    /**
     * @param null $filtersArray
     */
    public function setFiltersArray($filtersArray)
    {
        $this->filtersArray = $filtersArray;
    }

    public function initFiltersArray()
    {
        $filtersArray = $this->generateFiltersArray();
        $this->setFiltersArray($filtersArray);
    }

    /**
     * @return array
     */
    public function generateFiltersArray()
    {
        $filtersArray = [];
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            if ($filter->isActive()) {
                $filtersArray[$filter->getName()] = $filter->getValue();
            }
        }

        return $filtersArray;
    }
}
