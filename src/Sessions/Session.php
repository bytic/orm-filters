<?php

namespace Nip\Records\Filters\Sessions;

use ArrayObject;
use Nip\Http\Request;

/**
 * Class Session
 * @package Nip\Records\Filters\Sessions
 */
class Session extends ArrayObject
{
    use Traits\ArrayAccessTrait;
    use Traits\HasFiltersArrayTrait;
    use Traits\HasFiltersTrait;
    use Traits\HasQueryTrait;

    protected $name;

    protected $data;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $data
     */
    public function initWithData($data)
    {
        $this->setData($data);
        $data = $this->getData();
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            if ($data instanceof Request) {
                $filter->setRequest($data);
            } else {
                $filter->setValue(isset($data[$filter->getName()]) ? $data[$filter->getName()] : false);
            }
        }
    }
}
