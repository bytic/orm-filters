<?php

namespace Nip\Records\Filters\Tests\Sessions;

use Nip\Http\Request;
use Nip\Http\Request\PsrRequest;
use Nyholm\Psr7\ServerRequest;
use Nip\Records\Filters\Column\BasicFilter;
use Nip\Records\Filters\Sessions\Session;
use Nip\Records\Filters\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class SessionTest
 * @package Nip\Records\Filters\Tests\Sessions
 */
class SessionTest extends AbstractTest
{
    public function test_toArray()
    {
        $session = new Session();
        $session->addFilter((new BasicFilter())->setField('t1'));
        $session->addFilter((new BasicFilter())->setField('t2'));

        self::assertSame([], $session->toArray());
        self::assertSame([], (array) $session);
    }

    public function testInitWithCurrentRequestObjects()
    {
        $symfony = new SymfonyRequest(['title' => ' query ', 'zero' => 0], ['title' => 'body']);
        $symfony->attributes->set('title', ' attribute ');
        $psr = (new ServerRequest('POST', '/'))
            ->withQueryParams(['title' => ' query ', 'zero' => 0])
            ->withParsedBody(['title' => 'body'])
            ->withAttribute('title', ' attribute ');

        $requests = [$symfony, new Request(['title' => ' attribute ', 'zero' => 0]), $psr];
        if (class_exists(PsrRequest::class)) {
            $requests[] = new PsrRequest($symfony);
        }

        foreach ($requests as $request) {
            $session = new Session();
            $session->addFilter((new BasicFilter())->setField('title'));
            $session->addFilter((new BasicFilter())->setField('zero'));
            $session->addFilter((new BasicFilter())->setField('missing'));
            $session->initWithData($request);

            self::assertSame(['title' => 'attribute', 'zero' => 0], $session->getFiltersArray());
            self::assertSame($request, $session->getData());
        }
    }

    public function testPsrRequestReadsQueryAndParsedBodyWithoutNonstandardMethods()
    {
        $request = (new ServerRequest('POST', '/'))
            ->withQueryParams(['query' => 'query', 'shared' => 'query'])
            ->withParsedBody((object) ['body' => 'body', 'shared' => 'body']);
        $session = new Session();
        foreach (['query', 'body', 'shared'] as $name) {
            $session->addFilter((new BasicFilter())->setField($name));
        }
        $session->initWithData($request);

        self::assertSame(['query' => 'query', 'body' => 'body', 'shared' => 'query'], $session->getFiltersArray());
    }

    public function testRequestFieldsAndNullValuesRespectPrecedence()
    {
        $request = (new ServerRequest('POST', '/'))
            ->withQueryParams(['search' => '0', 'blocked' => 'query'])
            ->withParsedBody(['blocked' => 'body'])
            ->withAttribute('blocked', null);
        $filter = (new BasicFilter())->setField('title');
        $filter->setRequestField('search');
        $session = new Session();
        $session->addFilter($filter);
        $session->addFilter((new BasicFilter())->setField('blocked'));
        $session->initWithData($request);

        self::assertSame(['title' => '0'], $session->getFiltersArray());
    }

    public function testByticPsrRequestReadsJsonBody()
    {
        if (!method_exists(Request::class, 'getPSRRequest')) {
            self::markTestSkipped('This version of bytic/http does not provide the PSR request bridge.');
        }

        $request = new Request([], [], [], [], [], [
            'REQUEST_METHOD' => 'POST',
            'CONTENT_TYPE' => 'application/json',
        ], '{"title":" from json "}');
        $session = new Session();
        $session->addFilter((new BasicFilter())->setField('title'));
        $session->initWithData($request->getPSRRequest());

        self::assertSame(['title' => 'from json'], $session->getFiltersArray());
    }

    public function testReinitializationClearsPreviousValuesAndCachedArray()
    {
        $session = new Session();
        $session->addFilter((new BasicFilter())->setField('title'));
        $session->addFilter((new BasicFilter())->setField('missing'));
        $session->initWithData(['title' => 'first', 'missing' => 'old']);
        self::assertSame(['title' => 'first', 'missing' => 'old'], $session->getFiltersArray());

        $session->initWithData(new SymfonyRequest(['title' => ' second ']));
        self::assertSame(['title' => 'second'], $session->getFiltersArray());

        $session->initWithData(['title' => 'third']);
        self::assertSame(['title' => 'third'], $session->getFiltersArray());
    }
}