<?php

namespace Nip\Records\Filters\Sessions\Traits;

trait HasDataTrait
{

    protected $data;

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
}