<?php

namespace Nip\Records\Filters\Sessions;

use ArrayObject;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $this->setFiltersArray(null);
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            if ($data instanceof Request || $data instanceof ServerRequestInterface) {
                $filter->setRequest($data);
                $filter->setValue(null);
            } else {
                $filter->setRequest(null);
                $filter->setValue(isset($data[$filter->getName()]) ? $data[$filter->getName()] : false);
            }
        }
    }
}
