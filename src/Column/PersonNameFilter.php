<?php

namespace Nip\Records\Filters\Column;

use Nip\Database\Query\Select;
use Nip\Records\Filters\Column\AbstractFilter;

/**
 * Class PersonNameFilter
 * @package Nip\Records\Filters\Column
 */
class PersonNameFilter extends AbstractFilter
{
    /**
     * @inheritdoc
     */
    public function filterQuery($query)
    {
        if ($this->getField() == 'name') {
            return $this->filterQueryFullName($query);
        }
        if (in_array($this->getField(), ['first_name', 'last_name'])) {
            return $this->filterQueryNameFields($query);
        }

        return $this->filterQueryField($query);
    }

    /**
     * @param Select $query
     */
    protected function filterQueryField($query)
    {
        $field = $this->getField();
        $query->where('`' . $field . '` LIKE ?', '%' . $this->getValue() . '%');
    }

    /**
     * @param Select $query
     */
    protected function filterQueryNameFields($query)
    {
        $field = $this->getField();
        $names = explode(' ', $this->getValue());
        foreach ($names as $name) {
            $condition = $query->getCondition("`first_name` LIKE ? ", "%{$name}%")
                ->or_($query->getCondition("`last_name` LIKE ? ", "%{$name}%"));
            $query->where($condition);
        }
    }

    /**
     * @param Select $query
     */
    protected function filterQueryFullName($query)
    {
        $names = explode(' ', $this->getValue());
        foreach ($names as $name) {
            $condition = $query->getCondition("`first_name` LIKE ? ", "%{$name}%")
                ->or_($query->getCondition("`last_name` LIKE ? ", "%{$name}%"));
            $query->where($condition);
        }
    }
}
