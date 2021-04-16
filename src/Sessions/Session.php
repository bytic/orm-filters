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
    use Traits\HasDataTrait;
    use Traits\HasFiltersArrayTrait;
    use Traits\HasFiltersTrait;
    use Traits\HasQueryTrait;

    protected $name;
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
     * @param $data
     */
    public function initWithData($data)
    {
        $this->setData($data);
//        $data = $this->getData();
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
