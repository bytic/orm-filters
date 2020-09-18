<?php

namespace Nip\Records\Filters\Tests\Records;

use Mockery as m;
use Nip\Http\Request;
use Nip\Records\Filters\Tests\AbstractTest;
use Nip\Records\RecordManager as Records;

/**
 * Class HasFiltersRecordsTraitTest
 * @package Nip\Records\Filters\Tests\Records
 */
class HasFiltersRecordsTraitTest extends AbstractTest
{

    /**
     * @var Records
     */
    protected $testRecords;

    public function test_requestFilters()
    {
        $request = new Request();
        $params = [
            'title' => 'Test title',
            'name' => 'Test name',
        ];
        $request->query->add($params);

        $this->testRecords->getFilterManager()->addFilter(
            $this->testRecords->getFilterManager()->newFilter('Column\BasicFilter')
                ->setField('title')
        );

        $this->testRecords->getFilterManager()->addFilter(
            $this->testRecords->getFilterManager()->newFilter('Column\BasicFilter')
                ->setField('name')
        );

        $filtersArray = $this->testRecords->requestFilters($request);
        self::assertSame($filtersArray, $params);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->testRecords = m::mock(Records::class)->makePartial()
            ->shouldReceive('getRequest')->andReturn(Request::create('/'))
            ->getMock();
    }
}