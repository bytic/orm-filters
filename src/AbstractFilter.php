<?php

namespace Nip\Records\Filters;

use Nip\Database\Query\Select;
use Nip\Records\Filters\Filters\Traits\HasManagerTrait;
use Nip\Records\Filters\Filters\Traits\HasSessionTrait;
use Nip\Utility\Traits\HasRequestTrait;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class AbstractFilter
 * @package Nip\Records\Filters
 */
abstract class AbstractFilter implements FilterInterface
{
    use HasRequestTrait;
    use HasManagerTrait;
    use HasSessionTrait;

    /**
     * @var null|string
     */
    protected $name = null;

    /**
     * @var null|string
     */
    protected $requestField = null;

    /**
     * @var null|mixed
     */
    protected $value = null;

    /**
     * @param Select $query
     */
    abstract public function filterQuery($query);

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->hasValue();
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->getValue() !== false;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        if ($this->value === null) {
            $this->initValue();
        }

        return $this->value;
    }

    /**
     * @param string|false $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function initValue()
    {
        $value = $this->getProcessedRequestValue();
        $this->setValue($value);
    }

    /**
     * @return string|int|float|false
     */
    public function getProcessedRequestValue()
    {
        $value = $this->getValueFromRequest();
        if ($this->isValidRequestValue($value)) {
            return $this->cleanRequestValue($value);
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getValueFromRequest()
    {
        $request = $this->getRequest();
        $name = $this->getRequestField();
        if ($name === null || $name === '') {
            return false;
        }
        if ($request instanceof SymfonyRequest) {
            foreach ([$request->attributes, $request->query, $request->request] as $bag) {
                if ($bag->has($name)) {
                    return $bag->all()[$name];
                }
            }
        } elseif ($request instanceof ServerRequestInterface) {
            foreach ([$request->getAttributes(), $request->getQueryParams()] as $values) {
                if (array_key_exists($name, $values)) {
                    return $values[$name];
                }
            }

            $body = $request->getParsedBody();
            $body = is_object($body) ? get_object_vars($body) : $body;
            if (is_array($body) && array_key_exists($name, $body)) {
                return $body[$name];
            }
        }

        return false;
    }

    /**
     * @return null|string
     */
    public function getRequestField()
    {
        if ($this->requestField === null) {
            $this->initRequestField();
        }

        return $this->requestField;
    }

    /**
     * @param null|string $requestField
     */
    public function setRequestField($requestField)
    {
        $this->requestField = $requestField;
    }

    public function initRequestField()
    {
        $this->setRequestField($this->getName());
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        if ($this->name === null) {
            $this->initName();
        }

        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function initName()
    {
    }

    /**
     * @param string|false $value
     * @return bool
     */
    public function isValidRequestValue($value)
    {
        if (is_numeric($value)) {
            return true;
        }
        if (is_string($value)) {
            return !empty(trim($value));
        }

        return false;
    }

    /**
     * @param string|false $value
     * @return string
     */
    public function cleanRequestValue($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        return trim(stripslashes(htmlentities($value, ENT_QUOTES, 'UTF-8')));
    }

    /**
     * @return |null
     */
    public function __toString()
    {
        if ($this->isActive() == false) {
            return '';
        }
        return $this->getValue();
    }
}
