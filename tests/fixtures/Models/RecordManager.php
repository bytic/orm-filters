<?php

namespace Nip\Records\Filters\Tests\Fixtures\Models;

use Nip\Records\Filters\Records\HasFiltersRecordsTrait;

/**
 * Class RecordManager
 * @package Nip\Records\Filters\Tests\Fixtures\Models
 */
class RecordManager extends \Nip\Records\RecordManager
{
    use HasFiltersRecordsTrait;
}