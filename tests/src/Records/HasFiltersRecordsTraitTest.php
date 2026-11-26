<?php

namespace Nip\Records\Filters\Tests\Records;

use Mockery as m;
use Nip\Http\Request;
use Nip\Records\Filters\Tests\AbstractTest;
use Nip\Records\Filters\Tests\Fixtures\Models\RecordManager as Records;
use Nyholm\Psr7\ServerRequest;

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

    public function testRequestFiltersReplacesTheDefaultSession()
    {
        $manager = $this->testRecords->getFilterManager();
        $manager->addFilter($manager->newFilter('Column\BasicFilter')->setField('title'));

        self::assertSame(['title' => 'first'], $this->testRecords->requestFilters(['title' => 'first']));
        $request = (new ServerRequest('GET', '/'))->withQueryParams(['title' => 'second']);
        self::assertSame(['title' => 'second'], $this->testRecords->requestFilters($request));
        self::assertSame([], $this->testRecords->requestFilters([]));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->testRecords = m::mock(Records::class)->makePartial();
        $this->testRecords->shouldReceive('getRequest')->andReturn(Request::create('/'));
    }
}