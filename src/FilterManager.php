<?php

namespace Nip\Records\Filters;

use Nip\Collections\AbstractCollection;
use Nip\Collections\Traits\ArrayAccessTrait;
use Nip\Database\Query\Select as SelectQuery;
use Nip\Records\Filters\Column\AbstractFilter as AbstractColumnFilter;
use Nip\Utility\Traits\HasRequestTrait;

/**
 * Class FilterManager
 * @package Nip\Records\Filters
 *
 * @method AbstractFilter[]|AbstractColumnFilter[] all()
 * @method AbstractFilter|AbstractColumnFilter get($name)
 */
class FilterManager extends AbstractCollection
{
    use HasRequestTrait;
    use ArrayAccessTrait;
    use FilterManager\HasFiltersTrait;
    use FilterManager\HasSessionsTrait;
    use FilterManager\HasRecordManagerTrait;

    const DEFAULT_SESSION = 'default';

    /**
     * Init filter Manager, init default filters
     */
    public function init()
    {
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @param null $session
     * @return null
     */
    public function getFiltersArray($session = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $session = $this->getSession($session);
        return $session->getFiltersArray();
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @param SelectQuery $query
     * @param null $session
     * @return SelectQuery
     */
    public function filterQuery($query, $session = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $session = $this->getSession($session);
        return $session->filterQuery($query);
    }
}
